<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>PDF Generator API for Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                width: 100%;
                display: block;
            }

             a, label, select {
                color: #636b6f;
                padding: 0 10px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
                text-transform: uppercase;
            }

            select {
                padding: 5px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div id="pdf-generator-example" class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    PDF Generator API for Laravel
                </div>

                <div class="m-b-md">
                    <div class="m-b-md">
                        <label>Select template</label>
                        <select class="templates">
                            <optgroup label="Private Templates" class="private"></optgroup>
                            <optgroup label="Default Templates" class="default"></optgroup>
                        </select>
                    </div>
                    <div>
                        <a href="#" data-action="print" data-format="pdf">Print PDF</a>
                        <a href="#" data-action="print" data-format="html">Print HTML</a>
                        <a href="#" data-action="download" data-format="pdf">Download PDF</a>
                        <a href="#" data-action="download" data-format="html">Download HTML</a>
                        <a href="#" data-action="inline" data-format="pdf">Inline PDF</a>
                        <a href="#" data-action="inline" data-format="html">Inline HTML</a>
                        <a href="#" data-action="edit">Edit Template</a>
                        <a href="#" data-action="copy">Edit Template As New</a>
                    </div>
                </div>
                <div>
                    <textarea style="height: 300px; width: 100%" class="data">
[
  {
    "CustomerMemo": "Thank you for your business and have a great day!",
    "BillAddr": {
      "Line1": "Sasha Tillou\nFreeman Sporting Goods\n370 Easy St.\nMiddlefield, CA 94482",
      "City": "Middlefield",
      "Country": "United States",
      "CountryCode": "US",
      "CountrySubDivisionCode": "CA",
      "PostalCode": "94482"
    },
    "ShipAddr": {
      "Line1": "Sasha Tillou\nFreeman Sporting Goods\n370 Easy St.\nMiddlefield, CA 94482",
      "City": "Middlefield",
      "Country": "United States",
      "CountryCode": "US",
      "CountrySubDivisionCode": "CA",
      "PostalCode": "94482"
    },
    "DueDate": "2015-03-09",
    "TotalAmt": "497.50",
    "PrintStatus": "NeedToPrint",
    "EmailStatus": "NotSet",
    "BillEmail": {
      "Address": "Sporting_goods@intuit.com"
    },
    "Balance": "477.50",
    "DocNumber": "1036",
    "TxnDate": "2015-02-07",
    "Line": [
      {
        "Id": "1",
        "LineNum": "1",
        "Description": "Sod",
        "Amount": "200.00",
        "SalesItemLineDetail": {
          "Qty": 20,
          "UnitPrice": "10"
        }
      },
      {
        "Id": "2",
        "LineNum": "2",
        "Description": "2 cubic ft. bag",
        "Amount": "50.00",
        "TaxLineDetail": null,
        "SalesItemLineDetail": {
          "UnitPrice": "10",
          "Qty": "5"
        }
      },
      {
        "Id": "3",
        "LineNum": "3",
        "Description": "Weekly Gardening Service",
        "Amount": "87.50",
        "SalesItemLineDetail": {
          "UnitPrice": "25",
          "Qty": "3.5"
        }
      },
      {
        "Id": "4",
        "LineNum": "4",
        "Description": "Rock Fountain",
        "Amount": "275.00",
        "SalesItemLineDetail": {
          "UnitPrice": "275",
          "Qty": "1"
        }
      },
      {
        "Id": "5",
        "LineNum": "5",
        "Description": "Fountain Pump",
        "Amount": "15.00",
        "SalesItemLineDetail": {
          "UnitPrice": "15",
          "Qty": "1"
        }
      }
    ],
    "TxnTaxDetail": {
      "TotalTax": "20",
      "TaxLine": {
        "Amount": 20
      }
    },
    "SubTotalLineDetail": {"Amount": 477.50},

    "CompanyInfo": {
      "CompanyName": "My Sandbox Company",
      "LegalName": "My Sandbox Company",
      "CompanyAddr": {
        "Line1": "123 Sierra Way",
        "City": "Washington D.C",
        "Country": "United States",
        "CountryCode": "US",
        "CountrySubDivisionCode": "CA",
        "PostalCode": "87999"
      },
      "CompanyEmailAddr": "mycompany@gmail.com"
    },
    "CustomerInfo": {
     "Balance": "477.50",
      "PreferredDeliveryMethod": "Print",
      "GivenName": "Sasha",
      "FamilyName": "Tillou",
      "CompanyName": "Freeman Sporting Goods",
      "PrintOnCheckName": "Freeman Sporting Goods",
      "Email": "Sporting_goods@intuit.com"
    }
  }
]
                    </textarea>
                </div>
            </div>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
