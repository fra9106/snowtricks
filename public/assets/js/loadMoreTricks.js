window.onload = () => {
    $(document).ready(function() {
        $(".tricks-more").hide().slice(0, 4).css("display", "flex");
        $("#loadMore").click(function(){ 
        $(".tricks-more:hidden").slice(0, 2).css("display", "flex");
            if($(".tricks-more:hidden").length ===0){ 
                $("#loadMore").attr("disabled", "disabled");
                alert("No more!");
            }
        });

    })

}
