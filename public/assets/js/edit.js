window.onload = () => {
    /*let videos = document.querySelectorAll("[data-delete-video]")
    for(video of videos){
        video.addEventListener("click", function(e){
            e.preventDefault()
            if(confirm("Do you want to delete this video ?")){
            this.parentElement.remove();
            }
        })
    }*/

    let images = document.querySelectorAll("[data-delete-picture]")
    for(image of images){
        image.addEventListener("click", function(e){
            e.preventDefault()
            if(confirm("Do you want to delete this picture ?")){
            this.parentElement.remove();
            }
        })
    }

    function handleDeleteButtons() {
        $('a[data-action="delete"]').click(function(){
            const target = this.dataset.target;
            $(target).remove();
        });
    }
    
    handleDeleteButtons();
    
}
