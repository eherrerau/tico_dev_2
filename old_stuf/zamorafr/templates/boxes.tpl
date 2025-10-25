<head>
    {literal}
        <title>TICO-Tickets Control Center</title>
        <link href="/assets/css/index.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/casesAssignForm.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/graphBox.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/engineersBoxes.css" rel="stylesheet" type="text/css" />
    {/literal}       
</head>


 <div id="boxesMainSection">
    <div id="leyendDescriptionLink"><i class="icon-question-sign" style="color: #D7410B"></i>Leyend</div>
    <div id="leyendDescription">
        <ul>
            <li><i class="icon-circle" style="color:#B7CA34"></i>Foundation Case</li>
            <li><i class="icon-asterisk" style="color:#B7CA34"></i>Premier Case</li>
            <li><i class="icon-circle" style="color:#FF0000"></i>Critical Foundation Case</li>
            <li><i class="icon-asterisk" style="color:#FF0000"></i>Critical Premier Case</li>
            <li><i class="icon-phone-sign" style="color:#B7CA34"></i>Callback Foundation</li>
            <li><i class="icon-phone" style="color:#B7CA34"></i>Callback Premier</li>
            <li><i class="icon-phone-sign" style="color:#FF0000"></i>Critical Callback Foundation</li>
            <li><i class="icon-phone" style="color:#FF0000"></i>Critical Callback Premier</li>
            <li><i class="icon-circle-arrow-up" style="color:#B7CA34"></i>Elevation Foundation</li>
            <li><i class="icon-upload" style="color:#B7CA34"></i>Elevation Premier</li>
            <li><i class="icon-circle-arrow-up" style="color:#FF0000"></i>Critical Elevation Foundation</li>
            <li><i class="icon-upload" style="color:#FF0000"></i>Critical Elevation Premier</li>
            <li class="divider"></li>
            <li><i class="icon-star"></i>Premier Engineer</li>
            <li><i class="icon-home"></i>Status: Working from home</li>
            <li><i class="icon-thumbs-up"></i>Status: On Permission</li>
            <li><i class="icon-plane"></i>Status: Vacations</li>
            <li><i class="icon-book"></i>Status: Training</li>
            <li><i class="icon-medkit"></i>Status: Sick</li>
            <li><i class="icon-minus-sign"></i>Status: OOQ</li>
            <li><i class="icon-desktop"></i>Status: Queue Monitor</li>
            <li><i class="icon-suitcase"></i>Status: Holiday</li>
            <li><i class="icon-gift"></i>Status: Birthday</li>
        </ul>
    </div>                        
    <div id="engineerListBox">
        <div id="total_eng">{$counter}</div>
        <div id="engineerBoxTitle">Available</div>
        <div id="BoxColumnsLabel">
            <div id="engineerNameTitle"><b>Engineer</b></div>        
            <div id="in">In</div>
            <div id="lIn">Lunch</div>
            <div id="out">Out</div>
            <div id="especialIcons"></div>
            <div id="casesLabel">Cases</div>
        </div>

            <div id="boxRow">
                <div id="person">M - Andrea Martinez</div>
                <div id="in">10:00</div>
                <div id="lIn">13:00 - 14:00</div>
                <div id="out">19:00</div>
                <div id="cases"></div>
                <div id="especialIcons"></div>
            </div>
        
            <div id="boxRow">
                <div id="person">Mario Bolaños</div>
                <div id="in">10:00</div>
                <div id="lIn">13:00 - 14:00</div>
                <div id="out">19:00</div>
                <div id="cases"></div>
                <div id="especialIcons"></div>
            </div>
    </div>
</div>








