<div id="menus">    	
    <?php
    if (isProfile(3)) {
//        echo "<div id=\"leftMenu\">";
//        echo "<div id=\"leftMenu1\" onclick=\"addcase();\"><a href=\"#\" onclick=\"return false;\"></a></div>
//                    <div id=\"leftMenu2\" onclick=\"delcase();\"><a href=\"#\" onclick=\"return false;\"></a></div>
//                    <div id=\"leftMenu3\" onclick=\"modcase();\"><a href=\"#\" onclick=\"return false;\"></a></div>
//                    <div id=\"leftMenu4\" onclick=\"miscase();\"><a href=\"#\" onclick=\"return false;\"></a></div>";
//        echo "</div><!-- leftMenu -->";
        include("modules/casesAssignForm.php");
    } else {
        if (isProfile(2)) {
//            echo "<div id=\"leftMenu\">";
//            echo "  <div id=\"leftMenu4\" onclick=\"miscase();\">
//                        <a href=\"add.html\" onclick=\"return false;\"></a>
//                    </div>";
//            echo "</div><!-- leftMenu -->";
//            include("modules/casesAssignForm.php");
        }
    }
    ?> 
    
</div><!-- Menus -->