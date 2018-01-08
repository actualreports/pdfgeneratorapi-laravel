import _ from 'lodash';

export default class PDFGeneratorAPI {
  constructor (options) {
    this.routes = {
      basePath: '/pdfgenerator/templates',
      output: '/{template}/{output}/{format}',
      edit: '/{template}/edit',
      copy: '/{template}/copy',
      open: '/{template}/new',
      get: '/{template}',
      getAll: ''
    };

    options = options || {};
    if (options.routes)
    {
      this.routes = Object.assign(this.routes, options.routes);
    }

    this.editorOpen = false;
    this.closeCheckInterval = 2000;
    this.closeCheck = null;
  }

  init ()
  {
    let token = $('meta[name="csrf-token"]');
    if (token)
    {
      this.csrfToken = token.attr('content');
    }
    else
    {
      console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }

    this.createIFrame();
  }

  list ()
  {
    return $.ajax({
      url: this.getRoute('getAll'),
      method: 'GET'
    });
  }

  /**
   *
   * @param {Number} template
   * @param {String} format
   * @param {Object|Array} data
   * @returns {Promise}
   */
  print (template, format, data)
  {
    return this.output('print', template, format, data);
  }

  /**
   *
   * @param {Number} template
   * @param {String} format
   * @param {Object|Array} data
   * @returns {Promise}
   */
  download (template, format, data)
  {
    let output = 'download';
    let target = null;

    if (this.isMobile())
    {
      output = 'inline';
      target = '_blank';
    }

    return this.output(output, template, format, data, target);
  }

  /**
   * @param {Number} template
   * @param {String} format
   * @param {Object|Array} data
   * @returns {Promise}
   */
  inline (template, format, data)
  {
    return this.output('inline', template, format, data, '_blank');
  }

  output (output, template, format, data, target = null)
  {
    let route = this.getRoute('output', {
      template: template,
      output: output,
      format: format
    });

    return this.submitForm(route, data, target);
  }

  /**
   *
   * @param {Number} template
   * @param {Object|Array} data
   * @returns {Promise}
   */
  edit (template, data)
  {
    let route = this.getRoute('edit', {
      template: template
    });
    return this.openEditor(route, data);
  }

  /**
   *
   * @param {Number} template
   * @param {Object|Array} data
   * @param {String} newName
   * @returns {Promise}
   */
  copy (template, data, newName)
  {
    let route = this.getRoute('copy', {
      template: template
    }, {
      name: newName
    });
    return this.openEditor(route, data);
  }

  open (data)
  {
    return this.openEditor(this.getRoute('new'), data);
  }

  isEditorOpen()
  {
    return this.editorOpen;
  }

  /**
   * Returns promise that resolves when editor is closed and fails if unable to open editor window
   *
   * @param {String} route
   * @param {Object|Array} data
   * @returns {Promise}
   */
  openEditor (route, data)
  {
    let windowName = '_editorWindow';
    this.editorWindow = window.open('about:blank', windowName);
    this.editorOpen = true;
    this.submitForm(route, data, windowName);

    if (this.closeCheck)
    {
      clearInterval(this.closeCheck);
    }

    return new Promise((resolve, reject) => {
      this.closeCheck = setInterval(() => {
        try {
          if (this.editorWindow === null || this.editorWindow.closed)
          {
            this.closeCheck && clearInterval(this.closeCheck);
            resolve();
          }
        }
        catch (ex)
        {
          reject();
        }
      }, this.closeCheckInterval);
    });
  }

  /**
   *
   * @param {String} routeName
   * @param {Object} params
   * @param {Object} query
   * @returns {String}
   */
  getRoute(routeName, params = {}, query = null)
  {
    let route = this.routes.basePath + this.routes[routeName];
    for (let key in params)
    {
      route = route.replace('{'+key+'}', params[key]);
    }

    if (query)
    {
      let queryParams = [];
      for (let key in query)
      {
        queryParams.push(key+'='+ encodeURIComponent(query[key]));
      }
      route = route + '?' + queryParams.join('&');
    }

    return route;
  }

  createIFrame ()
  {
    let iFrameTarget = $('body');
    let iFrameId = _.uniqueId('iframe-');
    let tokenInput = $(document.createElement('input')).attr({
      type: 'hidden',
      name: '_token',
      value: this.csrfToken
    });

    this.iFrame = $(document.createElement('iframe')).attr({
      id: iFrameId,
      name: iFrameId,
      style: 'position: absolute; left: -12000px;'
    }).appendTo(iFrameTarget);

    this.iFrameForm = $(document.createElement('form'))
      .attr({
        method: 'post'
      })
      .css({
        display: 'none'
      });

    this.iFrameFormData = $(document.createElement('textarea'))
      .attr('name', 'data')
      .appendTo(this.iFrameForm);

    this.iFrameForm.appendTo(iFrameTarget);
    this.iFrameForm.append(tokenInput);
  }

  /**
   *
   * @param {String} route
   * @param {Object|String} data
   * @param {String} target
   * @returns {Promise}
   */
  submitForm (route, data, target = null)
  {
    if (!target)
    {
      target = this.iFrame.attr('id');
    }

    let targetIFrame = target === this.iFrame.attr('id');

    this.iFrame.off('load');
    this.iFrame.off('load.error');

    return new Promise((resolve, reject) => {
      /**
       * If iframe is targeted then resolve promise on iframe load
       */
      if (targetIFrame)
      {
        this.iFrame.on('load', () => {
          this.iFrame.off('load');
          resolve();
        });

        this.iFrame.on('load.error', () => {
          this.iFrame.off('load.error');
          reject();
        });
      }

      this.iFrameFormData.val(_.isString(data) ? data : JSON.stringify(data));
      this.iFrameForm.attr({
        action: route,
        target: target || this.iFrame.attr('id')
      }).submit();

      /**
       * If iframe is not targeted resolve promise after submitting the form
       */
      if (!targetIFrame)
      {
        resolve();
      }
    });
  }

  isMobile ()
  {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  }
}