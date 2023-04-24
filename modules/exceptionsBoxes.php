<div id="exception">
                        <form name="exceptionForm"  id="exceptionForm" method="post" action="include/insertSchedExcep.php">
                            <div id="excTitle"><h1>Schedule Exceptions</h1></div>                            
                            <div id="engineerNameTitle">Engineer:</div>
                            <div id="engineerNameList">
                                <?php include_once 'include/getFullEngineerList.php'; ?>
                            </div>                            
                            <div id="tipo2">Type:</div>
                            <div id="schTypeList"><select name="schedExecList"><?php echo $schedList = implode(getSchedExcepList()) ?></select></div>
                            <div id="timeAll">All day:<input name="allDay" id="allDay" type="checkbox" value="1"/></div>
                            <div id="dateFrom">
                                Date From:<input type="text" name="datepickerFrom" id="datepickerFrom" value="" size="10">
                            </div><!-- End dateFrom -->
                            <div id="dateTo">
                                Date to: <input type="text" name="datepickerTo" id="datepickerTo" value="" size="10">
                            </div><!-- End dateTo -->                
                            <div id="timeFrom">From:<select name="finh">
                                <?php echo getHours(); ?>
                                </select>:<select name="finmin">
                                    <?php echo getMinutes(); ?>
                                </select></div>
                            <div id="timeTo">To:<select name="tinh">
                                <?php echo getHours(); ?>
                                </select>:<select name="tinmin">
                                <?php echo getMinutes(); ?>
                                </select></div>                            
                            <div id="createExc" class="genericPrimaryButtonSlim" onClick="javascript:document.exceptionForm.submit()"><a href="include/JavascriptRequired.php" title="Create Exception" onClick="return false">Create</a></div>
                        </form>	
                    </div><!-- exception -->
                    <div id="schedExcepListForUser"><h1>Schedule Exceptions for the last 30 days:</h1>
                        <?php echo implode(" ", getNextSchedExcep($profileDetails[0])); ?>
                    </div><!-- info -->