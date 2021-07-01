
<?php
require_once("include/header.php");
?>



<div class="textboxContainer">
    <input type="text" class="searchInput" placeholder="Search for something">
</div>

<div class="searchResults">
    <!-- SEARCH RESULTS HERE -->
</div>



<script>

    $(function(){

        var username='<?php echo $userLoggedin; ?>';
        var timer;
        console.log(username);

        $(".searchInput").keyup(function(){
            clearTimeout(timer);
            console.log("Timer cleared")

            timer=setTimeout(function(){
                var val=$(".searchInput").val();
                console.log(val);

                if(val!=""){

                    $.post("ajax/getSearchResults.php",{term:val,username:username},function(data){
                        $(".searchResults").html(data);
                    })
                    console.log("Show results");
                }
                else{
                    $(".searchResults").html("");
                }
            },500)
        })
    })

</script>