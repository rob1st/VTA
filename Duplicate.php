<HTML>
    <HEAD>
        <TITLE>Duplicate Entry</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
        
    </HEAD>
    <BODY>
<div class="backgrounds">
<div class="main-content"> 
<?php include('Nav.html') ?>
<div class="sub-content"> 
        <h1>This entry already exists</h1>
        
        <p>Depending on which page you came from, the table below will tell you the duplicate fields</p>

        <table>
            <tr>
                <th>Form</th>
                <th>Duplicate searches for:</th>
            </tr>
            <tr>
                <td>New Deficiency</td>
                <td>Location AND Description</td>
            </tr>
            <tr>
                <td>New User</td>
                <td>Username</td>
            </tr>
            <tr>
                <td>New Location</td>
                <td>Location name</td>
            </tr>
            <tr>
                <td>New Severity</td>
                <td>Severity Name</td>
            </tr>
            <tr>
                <td>New Status</td>
                <td>Date AND Gender AND (Home Team OR Away Team)</td>
            </tr>
        </table>
        </div>
        </div>
        </div>
    </body>
</table>