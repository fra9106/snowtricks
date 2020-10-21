window.onload = () => {
    let videos = document.querySelectorAll("[data-delete]")
    for(video of videos){
        video.addEventListener("click", function(e){
            e.preventDefault()
            if(confirm("Do you want to delete this video ?")){
            this.parentElement.remove();
            }
        })
    }
}
