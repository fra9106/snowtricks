window.onload = () => {
    $(document).ready(function() {
        $(".tricks-more").hide().slice(0, 5).css("display", "flex");
        $(".scroll-to-up").hide();
        
        $("#loadMore").click(function(){ 
        $(".tricks-more:hidden").slice(0, 5).css("display", "flex");
        if($(".tricks-more:hidden").length ===5){
            $(".scroll-to-up").show(); 
         }
            if($(".tricks-more:hidden").length ===0){ 
                $("#loadMore").attr("disabled", "disabled");
                alert("No more!");
            }
        });

    })
   
}
