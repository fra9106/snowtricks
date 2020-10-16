window.onload = () => {
    // Manage the "Delete" buttons
    let links = document.querySelectorAll("[data-video-delete]")
    
    // loop on links
    for(link of links){
        // we listener the click
        link.addEventListener("click", function(e){
            // We prevent navigation
            e.preventDefault()

            //confirmation
            if(confirm("Do you want to delete this video ?")){
                // request Ajax to the href of the link with the DELETE method
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e))
            }
        })
    }
}
