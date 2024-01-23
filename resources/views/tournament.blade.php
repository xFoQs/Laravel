<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
    <title>Tournament Bracket Maker</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />

    <link rel="stylesheet" type="text/css" href="css/style.css">

    <style>
        #import-teams-btn-wrapper {
            display: inline-block;
            position: relative;
        }

        #import-teams-tooltip {
            visibility: hidden;
            width: 120px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        #import-teams-btn-wrapper:hover #import-teams-tooltip {
            visibility: visible;
            opacity: 1;
        }


        #add-entry-btn {
            font-family: Roboto, sans-serif;
            font-weight: 800;
            font-size: 14px;
            color: #fff;
            background-color: #3637a8;
            padding: 10px 32px;
            border: solid #ffffff 2px;
            box-shadow: rgb(0, 0, 0) 0px 0px 0px 0px;
            border-radius: 50px 50px 50px 0px;
            transition: 873ms;
            transform: translateY(0);
            display: flex;
            flex-direction: row;
            align-items: center;
            cursor: pointer;
            text-transform: uppercase;
        }

        #add-entry-btn:hover {
            transition: 873ms;
            padding: 10px 34px;
            transform: translateY(-0px);
            background-color: #202179;
            color: white;
            border: solid 2px #0066cc;
        }

        #make-bracket-btn {
            font-family: Roboto, sans-serif;
            font-weight: 800;
            font-size: 14px;
            color: #fff;
            background-color: #3637a8;
            padding: 10px 32px;
            border: solid #ffffff 2px;
            box-shadow: rgb(0, 0, 0) 0px 0px 0px 0px;
            border-radius: 50px 50px 50px 0px;
            transition: 873ms;
            transform: translateY(0);
            display: inline-block;
            text-align: center;
            cursor: pointer;
            text-transform: uppercase;
        }

        #make-bracket-btn:hover {
            transition: 873ms;
            padding: 10px 34px;
            transform: translateY(-0px);
            background-color: #202179;
            color: white;
            border: solid 2px #0066cc;
        }

        #import-teams-btn-wrapper {
            font-family: Roboto, sans-serif;
            font-weight: 800;
            font-size: 14px;
            color: #fff;
            background-color: #3637a8;
            padding: 10px 32px;
            border: solid #ffffff 2px;
            box-shadow: rgb(0, 0, 0) 0px 0px 0px 0px;
            border-radius: 50px 50px 50px 0px;
            transition: 873ms;
            transform: translateY(0);
            display: inline-block;
            text-align: center;
            cursor: pointer;
            text-transform: uppercase;
        }

        #import-teams-btn-wrapper:hover {
            transition: 873ms;
            padding: 10px 34px;
            transform: translateY(-0px);
            background-color: #202179;
            color: white;
            border: solid 2px #0066cc;
        }


        #download-btn {
            font-family: Roboto, sans-serif;
            font-weight: 800;
            font-size: 14px;
            color: #fff;
            background-color: #3637a8;
            padding: 10px 32px;
            border: solid #ffffff 2px;
            box-shadow: rgb(0, 0, 0) 0px 0px 0px 0px;
            border-radius: 50px 50px 50px 0px;
            transition: 873ms;
            transform: translateY(0);
            display: flex;
            flex-direction: row;
            align-items: center;
            cursor: pointer;
            text-transform: uppercase;
        }

        #download-btn:hover {
            transition: 873ms;
            padding: 10px 34px;
            transform: translateY(-0px);
            background-color: #202179;
            color: white;
            border: solid 2px #0066cc;
        }

    </style>
</head>

<!-- background and page padding -->

<body id="wrapper-main" class="bg-white">
<div id="wrapper-header" class="bg-light-gray rounded header" style="color: white;">
    <div>Drabinka Turniejowa</div>
</div>

<div id="wrapper-content">
    <div id="content">
        <div id="content-left-pane" class="rounded bg-light-gray">
            <div id="left-pane-header" class="verdana-gray" style="color: white;">
                <div>Lista:</div>
            </div>

            <div id="left-pane-content">
                <div id="e-list"></div>
            </div>
        </div>

        <div id="content-main" style="padding-right: 2px;">
            <div id="form" class="bg-light-gray rounded" style="padding-right: 2px;">
                <div id="add-entry">
                    <div id="add-entry-prompt" class="verdana-gray">
                        <span style="color: white;">Nazwa drużyny:</span>
                        <input id="e-input" type="text" style="padding: 5px;">
                    </div>
                    <div id="add-entry-btn-wrapper">
                        <button id="add-entry-btn" class="button-style" onclick="addEntry()">Dodaj</button>
                    </div>
                </div>

                <!-- Dodaj przycisk importu -->
                <div id="import-teams-btn-wrapper">
                    <label for="teams-file">
                        <input type="file" id="teams-file" accept=".csv" style="display:none" onchange="handleTeamsFile()">
                        Wczytaj Drużyny
                    </label>
                </div>

                <div id="make-bracket-btn-wrapper">
                    <div id="make-bracket-btn" class="form-btn" onclick="makeBracket()">
                        Stwórz Bracket
                    </div>
                </div>
            </div>

            <div id="bracket" class="verdana-gray">
                <!-- FILLS ON makeBracket() CLICK -->
            </div>
        </div>
    </div>
</div>

<div id="wrapper-footer" class="bg-light-gray rounded">
    <div id="footer" class="verdana-gray">
        <div id="link-wrapper">
            <!-- Add the download button here -->
            <div id="download-btn" onclick="downloadBracket()">Pobierz drabinkę</div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="js/globals.js"></script>
<script src="js/utils.js"></script>
<script src="js/Entry.js"></script>
<script src="js/EntriesList.js"></script>
<script src="js/Bracket.js"></script>
<script src="js/controller.js"></script>
<script src="js/event_listeners.js"></script>

<script>
    function downloadBracket() {
        const element = document.getElementById('bracket');

        html2canvas(element, {
            width: element.scrollWidth, // Specify a custom width
            height: element.scrollHeight, // Specify a custom height
        }).then(canvas => {
            canvas.toBlob(function (blob) {
                saveAs(blob, 'tournament_bracket.png');
            });
        });
    }
</script>


</body>

</html>
