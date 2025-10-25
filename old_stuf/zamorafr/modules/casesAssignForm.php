<div id="CRUDCases">
    <!-------------- ADD A CASE -------------------->
    <form action="include/insertCase.php" method="post" name="addForm">
        <div id="caseNumHolder">
            <div id="caseNum_lb">Case #</div>
            <div id="caseNum_tb">
                <input name="casetxt" tabindex="1" type="text" size="10" maxlength="10" />
            </div>
            <div id="searchCaseHistory_txt">
                <a href="javascript:searchCase(1)">                    
                    <i class="icon-search" style="text-decoration:none"></i>
                </a>
            </div>
        </div>
        <div id="typeOfCaseHolder">
            <div id="typeOfCase_LB">As</div>
            <div id="typeOfCase_ck">
                <select id="ascallback" tabindex="2" name="ascallback">
                    <option value="Normal">Normal</option>
                    <option value="Callback">Callback</option>
                    <option value="Elevation">Elevation</option>
                </select>
            </div>
        </div>
        <div id="impactHolder">
            <div id="impact_lb">Impact</div>
            <div id="impact_select">
                <select id="severitylst" name="severitylst" tabindex="3">
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>
            </div>           
        </div>
        <div id="premierHoder">
            <div id="premier_lb">Premier</div>
            <div id="premier_ckb">             
                <select id="premier" name="premier" tabindex="4" onChange="getenglst()">
                    <option value="No" class="icon-circle">No</option>
                    <option value="Yes">
                    <i class="icon-asterisk">Yes</i>                        
                    </option>
                </select>
            </div>
        </div>
        <div id="productHolder">
            <div id="product_lb">Product</div>
            <div id="product"><?php include("/include/getProducts.php"); ?></div>
        </div>        
        <div id="engineerHolderAdd">
            <div id="engineer_lb">Engineer</div>
            <div id="engineerADD_dpb"></div>
        </div>  
        <div id=messages></div>
        <div id="formButtonHolder">
            <div id="formSubmitDel" tabindex="9" class="genericTertiaryButtonSlim">
                <a href="javascript:deleteCase()" >Delete</a>
            </div>            
            <div id="formSubmitMod" tabindex="8" class="genericSecondaryButtonSlim" >
                <a href="javascript:modifyCase()">Modify</a>
            </div>
            <div id="formSubmitAdd" tabindex="7" class="genericPrimaryButtonSlim">
                <a href="javascript:newCaseSubmit()">Add</a>
            </div>
        </div>
        <div id="searchCaseHistory_lb" onclick="history('include/caseHistory.php', document.addForm.casetxt.value);" class="genericTertiaryButtonSlim">
            <a href="include/caseHistory.php" onclick="return false;">History</a>
        </div>
    </form>
    

    <!----------------Reports------------------>
<!--    <form action="submit" method="get" name="misForm" >
        <div id="formTitleLabel">Submit a misrouted caseCreate Reports</div>
        <div id="caseNum_lb">From</div>
        <div id="caseNum_tb"><input type="text" id="datepicker3" size="10" tabindex="1"></div>
        <div id="addForm16">To</div>
        <div id="addForm17"><input type="text" id="datepicker4" size="10"></div>
        <div id="addForm18">Report</div>
        <div id="addForm19">
            <select id="reportType" name="reportType"  onChange="getenglst3()">
                <option value="1">Team Average by engineer</option>
                <option value="2">Premier Average by engineer</option>
                <option value="3">Foundation Average by engineer</option>
                <option value="4">All Total Cases by engineer</option>
                <option value="5">Total Cases Premier by engineer</option>
                <option value="6">Total Cases Foundation by engineer</option>
                <option value="7">Cases by engineer</option>       
                <option value="8">All Critical Cases by engineer</option>  
                <option value="9">Foundation Critical Cases by engineer</option>   
                <option value="10">Premier Critical Cases by engineer</option>                
            </select>
        </div>
        <div id="premier_lb">Engineer</div>
        <div id="addForm44">  </div>
        <div id="formSubmitAdd">
            <a href="javascript:graphicAv('graphs/graphAvPeriod.php')">Create</a>
        </div>
    </form>    -->
</div>
