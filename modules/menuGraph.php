<div id="schedMenus">
    <?php
    if (isProfile(3) or isProfile(1) or isProfile(2)) {//if is a QM, admin or manager
        echo "
            <div id=\"opt6\" onclick=\"todaycases('graphs/todayCasesList.php');\">
                <a href=\"todayCasesList.php\" class=\"genericOrangeButtonSlim\" onclick=\"return false;\">Cases</a>
            </div>";
            //<div id=\"opt1\" onclick=\"graphic('graphs/graphDay.php');\"><a href=\"graphDay.php\"  onclick=\"return false;\">Day</a></div>
        echo "
            <div id=\"opt2\" onclick=\"graphic('graphs/graphWeek.php');\">
                <a href=\"graphWeek.php\" onclick=\"return false;\">Week</a>
            </div>
	<div id=\"opt3\" onclick=\"graphic('graphs/graphmonth.php');\">
            <a href=\"graphmonth.php\"  onclick=\"return false;\">Month</a>
        </div>";
        echo"
            <div id=\"opt4\" onclick=\"graphic('graphs/graphAverage.php');\">
                <a href=\"graphAverage.php\"  onclick=\"return false;\">Avg Foundation</a>
            </div>
            <div id=\"opt4\" onclick=\"graphic('graphs/graphAveragePremier.php');\">
                <a href=\"graphAverage.php\"  onclick=\"return false;\">Avg Premier</a>
            </div>
            <div id=\"opt6\" onclick=\"graphic('graphs/graphPremier.php');\">
                <a href=\"graphPremier.php\"  onclick=\"return false;\">Premier</a>
            </div>  ";            
    }
    if (isProfile(5) or isProfile(4) or isProfile(3) or isProfile(1)) {// if is an engineer, expert, QM or admin
        include 'graphs/myStatisticsGraph.php';
//        echo"
//            <div id=\"opt3\" onclick=\"graphic('graphs/mygraphMonth.php');\">
//                <a href=\"mygraphMonth.php\"  onclick=\"return false;\">My Statistics</a>
//            </div>";
    }
    ?>
</div>