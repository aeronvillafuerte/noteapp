const addBox = document.querySelector(".add-box");
const popupBox = document.querySelector(".popup-box");
const popupTitle = popupBox.querySelector("header p");
const closeIcon = popupBox.querySelector("header i");
const titleTag = popupBox.querySelector("input");
const descTag = popupBox.querySelector("textarea");
const addBtn = popupBox.querySelector("button");

const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

//getting localstorage notes if exist in parsing them   
// to js object else passing an empty array to notes 
const notes = JSON.parse(localStorage.getItem("notes") || "[]");
let isUpdate = false, updateId;

// Add event listener to open popup box
addBox.addEventListener("click", () => {
    titleTag.focus();
    popupBox.classList.add("show");
});

closeIcon.addEventListener("click", () => {
    titleTag.value = "";
    descTag.value = "";
    addBtn.innerText = "Add Note";
    popupTitle.innerText = "Add a new Note";
    popupBox.classList.remove("show");
});

// Function to display notes
function showNotes() {
    // Fetch notes from the server
    fetch('dashboardd.php')
        .then(response => response.json())
        .then(notes => {
            // Clear existing notes
            document.querySelector(".notes-container").innerHTML = "";
            // Iterate over fetched notes and display them
            notes.forEach((note, index) => {
                let liTag = `<li class="note">
                                <div class="details">
                                    <p>${note.title}</p>
                                    <span>${note.content}</span>
                                </div>
                                <div class="bottom-content">
                                    <span>${note.created_at}</span>
                                    <div class="settings">
                                        <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                                        <ul class="menu">   
                                            <li onclick="updateNote(${index}, '${note.title}', '${note.content}')"><i class="uil uil-pen"></i>Edit</li>
                                            <li onclick="deleteNote(${index})"><i class="uil uil-trash"></i>Delete</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>`;
                document.querySelector(".notes-container").insertAdjacentHTML("beforeend", liTag);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Call showNotes to display existing notes
showNotes();

function showMenu(elem) {
    elem.classList.toggle("show");
    document.addEventListener("click", e => {
        // removing show class from the settings menu on document click
        if (e.target.tagName != "I" || e.target != elem) {
            elem.parentElement.classList.remove("show");
        }
    });
}

// Function to show the edit popup box with note details
function showEditPopup(noteId, title, content) {
    // Set values for the edit popup fields
    document.getElementById('edit-note-id').value = noteId;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-content').value = content;

    // Show the edit popup box
    document.querySelector('.edit-popup-box').classList.add('show');
}

    // Function to close the edit popup box
    function closeEditPopup() {

    // Hide the edit popup box
    document.querySelector('.edit-popup-box').classList.remove('show');
}

// Function to delete a note
function deleteNote(noteId) {
    // Send a request to delete_note.php to delete the note from the database
    fetch('delete_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'note_id=' + noteId,
    })
    .then(response => response.text())
    .then(data => {
        // Display the response message
        alert(data);
        // Reload the dashboard to reflect changes
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


// Function to show the edit popup box with note details
function showEditPopup(noteId, title, content) {
    // Set values for the edit popup fields
    document.getElementById('edit-note-id').value = noteId;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-content').value = content;

    // Show the edit popup box
    document.querySelector('.edit-popup-box').classList.add('show');
}

document.addEventListener("DOMContentLoaded", function() {
    // Function to handle form submission for updating a note
    document.getElementById("update-submit-btn").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission

        // Get the updated title and content from the form
        var updatedTitle = document.getElementById("edit-title").value;
        var updatedContent = document.getElementById("edit-content").value;
        
        // Get the note ID
        var noteId = document.getElementById("edit-note-id").value;

        // Send the updated data to the server via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_note.php", true); // Change "update_note.php" to the appropriate server endpoint
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Update the note on the page if update was successful
                    var noteElement = document.getElementById("note-" + noteId);
                    noteElement.querySelector(".note p").textContent = updatedTitle;
                    noteElement.querySelector(".note span").textContent = updatedContent;
                    
                    // Close the edit popup
                    closeEditPopup();
                } else {
                    // Handle error
                    console.error("Error updating note: " + xhr.responseText);
                }
            }
        };
        // Send the updated title, content, and note ID to the server
        var formData = "title=" + encodeURIComponent(updatedTitle) + "&content=" + encodeURIComponent(updatedContent) + "&note_id=" + noteId;
        xhr.send(formData);
    });
});

// Hide notes when scrolling up
window.addEventListener('scroll', function() {
    var scrollPosition = window.scrollY;
    var allNotesSection = document.querySelector('.all-notes');
    var searchBar = document.querySelector('.search-container');
    var notesWrapper = document.querySelector('.notes-wrapper');

    if (scrollPosition > 0) {
        allNotesSection.style.top = '-100px'; // Move "All Notes" section out of view
        searchBar.style.top = '-100px'; // Move search bar out of view
        notesWrapper.style.paddingTop = '0'; // Remove padding from notes wrapper to bring notes to the top
    } else {
        allNotesSection.style.top = '0';
        searchBar.style.top = '30px'; // Adjust as needed based on your layout
        notesWrapper.style.paddingTop = '50px'; // Restore original padding when scrolled to top
    }
});

function toggleStar(element, noteId) {
    if (!element.classList.contains("starred")) {
        // Star is not filled, so fill it
        element.classList.add("starred");
        element.classList.remove("fa-star-o");
        element.classList.add("fa-star");
        // Send AJAX request to add note to favorites
        addToFavorites(noteId);
    } else {
        // Star is filled, so unfill it
        element.classList.remove("starred");
        element.classList.remove("fa-star");
        element.classList.add("fa-star-o");
        // Send AJAX request to remove note from favorites
        removeFromFavorites(noteId);
    }
}

function addToFavorites(noteId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Handle response if needed
        }
    };
    xhttp.open("POST", "favorites.php", true); // Update the URL to favorites.php
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_id=" + noteId); // Pass note_id as a parameter
}

function removeFromFavorites(noteId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Handle response if needed
        }
    };
    xhttp.open("POST", "favorites.php", true); // Update the URL to favorites.php
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_id=" + noteId); // Pass note_id as a parameter
}










 