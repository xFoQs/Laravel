<!DOCTYPE html>
<html lang="en" style="height: 100%">
<head>
    <title>Tournament Bracket Maker</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content=""/>

    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<!-- background and page padding -->
<body id="wrapper-main" class="bg-white">
<div id="wrapper-header" class="bg-light-gray rounded verdana-gray header">
    <div>Tournament Bracket Maker</div>
</div>

<div id="wrapper-content">
    <div id="content">
        <div id="content-left-pane" class="rounded bg-light-gray">
            <div id="left-pane-header", class="verdana-gray">
                <div>Lista:</div>
            </div>

            <div id="left-pane-content">
                <div id="e-list"></div>
            </div>
        </div>

        <div id="content-main">
            <div id="form" class="bg-light-gray rounded">
                <div id="add-entry">
                    <div id="add-entry-prompt" class="verdana-gray">
                        <span>Nazwa drużyny:</span>
                        <input id="e-input" type="text">
                    </div>
                    <div id="add-entry-btn-wrapper">
                        <div id="add-entry-btn" class="form-btn" onclick="addEntry()">
                            Dodaj
                        </div>
                    </div>
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

        </div>
    </div>
</div>

<script src="js/globals.js"></script>
<script src="js/utils.js"></script>
<script src="js/Entry.js"></script>
<script src="js/EntriesList.js"></script>
<script src="js/Bracket.js"></script>
<script src="js/controller.js"></script>
<script src="js/event_listeners.js"></script>

</body>
</html>
