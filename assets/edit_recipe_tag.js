
const addTagFormDeleteLink = (tagFormLi) => {
    console.log(tagFormLi)
    const removeFormButton = document.createElement('button')
    removeFormButton.classList
    removeFormButton.innerText = 'Delete this tag'
    
    tagFormLi.append(removeFormButton);
    
    removeFormButton.addEventListener('click', (e) => {
            e.preventDefault()
            // remove the li for the tag form
            tagFormLi.remove();
        });
    }
    
    const tags = document.querySelectorAll('ul.tags>li')
    tags.forEach((tag) => {
        console.log(tag)
        addTagFormDeleteLink(tag)
        
    })
    

  