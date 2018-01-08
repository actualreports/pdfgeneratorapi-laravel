/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import PDFGeneratorAPIClass from '../vendor/pdfgeneratorapi';

window.Vue = require('vue');

let PDFGeneratorAPI = new PDFGeneratorAPIClass();
$(() => {
  let el = $('#pdf-generator-example');
  let templates = el.find('.templates');
  let dataContainer = el.find('.data');
  let prettyPrintJson = () => {
    let value = dataContainer.val();
    dataContainer.css('border', 'solid 1px #0ad6bc');

    if (value)
    {
      try {
        dataContainer.val(JSON.stringify(JSON.parse(value), undefined, 4));
      }
      catch (e)
      {
        dataContainer.css('border', 'solid 2px #bd4242');
      }
    }
  };

  let loadTemplates = () => {
    PDFGeneratorAPI.list().then((response) => {
      let defaultGroup = templates.find('.default').empty();
      let privateGroup = templates.find('.private').empty();

      response.private.forEach((template) => {
        privateGroup.append($(document.createElement('option')).attr('value', template.id).text(template.name));
      });
      response.default.forEach((template) => {
        defaultGroup.append($(document.createElement('option')).attr('value', template.id).text(template.name));
      });

      templates.val(response.default[0].id);
    });
  };

  /**
   * Initialize PDG Generator API
   */
  PDFGeneratorAPI.init();

  /**
   * Load initial templates
   */
  loadTemplates();

  /**
   * Pretty print json
   */
  dataContainer.on('blur', prettyPrintJson);
  prettyPrintJson();

  el.on('click', 'a[data-action]', function (event) {
    let link = $(this);
    let action = link.data('action');
    let format = link.data('format');
    let template = templates.val();
    let data = dataContainer.val();

    if (data)
    {
      data = JSON.parse(data);
    }

    if (action === 'print')
    {
      PDFGeneratorAPI.print(template, format, data).then(() => {
        console.log('resolve print promise');
      });
    }
    else if (action === 'download')
    {
      PDFGeneratorAPI.download(template, format, data).then(() => {
        console.log('resolve download promise');
      });
    }
    else if (action === 'inline')
    {
      PDFGeneratorAPI.inline(template, format, data).then(() => {
        console.log('resolve inline promise');
      });
    }
    else if (action === 'edit')
    {
      PDFGeneratorAPI.edit(template, data).then(() => {
        console.log('editor closed, reload templates');
        loadTemplates();
      });
    }
    else if (action === 'copy')
    {
      PDFGeneratorAPI.copy(template, data, 'New name for copied template').then(() => {
        console.log('editor closed, reload templates');
        loadTemplates();
      });
    }

    event.preventDefault();
    return false;
  });
});
