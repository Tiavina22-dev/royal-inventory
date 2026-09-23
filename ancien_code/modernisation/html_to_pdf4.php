<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
     <style type="text/css">
        body
        {
            font-family: Arial;
            font-size: 16pt;
        }
        table
        {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }
        table th
        {
            background-color: #F7F7F7;
            color: #333;
            font-weight: bold;
        }
        table th, table td
        {
            padding: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
    </style>
</head>
<body>
     <table id="tblCustomers" cellspacing="0" cellpadding="0">
        <tr>
            <th colspan="3">MAGASIN ROYAL
Vente pièces détachées Auto-Moto-Tracteur
 QUINCAILLERIE-APPAREILS ELECTRONIQUES 
Ambaiboho-Amparafa-Bjofo-Ambato
Stat:51101332007000100
Nif:2000042321
Tél:0345042209  0342822996 0343961560
Email: royalambaiboho@gmail.com
Tsy azo averina na atakalo ny entana lafo</th>
        </tr>
        <tr>
            <td>1</td>
            <td>John Hammond</td>
            <td>United States</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Mudassar Khan</td>
            <td>India</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Suzanne Mathews</td>
            <td>France</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Robert Schidner</td>
            <td>Russia</td>
        </tr>
    </table>
    <br />
    <input type="button" id="btnExport" value="Export" onclick="Export()" />
    <script type="text/javascript" src="js/pdfmake.min.js"></script>
    <script type="text/javascript" src="js/html2canvas.min.js"></script>
    <script type="text/javascript">
        function Export() {
            html2canvas(document.getElementById('tblCustomers'), {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                      // a string A4 A5 pageSize: 'A5' or pageSize: { width: number, height: number }
                      pageSize: { width: 400, height: 842.71 },
                      // by default we use portrait landscape, you can change it to landscape if you wish
                      pageOrientation: 'portrait',
                      // Orientation
                      pageMargins: [ 5, 5, 5, 5 ],
                        content: [{
                            image: data,
                            width: 380
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Table2.pdf");
                }
            });
        }
    </script>
</body>
</html>