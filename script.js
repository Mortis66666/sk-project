const darkModeToggle = document.getElementById("darkModeToggle");
const body = document.body;
const header = document.getElementById("header");
const content = document.getElementById("content");
const iconToggle = document.getElementById("icon-toggle");

darkModeToggle.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    header.classList.toggle("dark-mode");
    content.classList.toggle("dark-mode");

    console.log(iconToggle.classList);

    if (iconToggle.classList.contains("dark-mode-icon")) {
        iconToggle.className = "fa-solid fa-moon fa-2xl";
    } else {
        iconToggle.className = "dark-mode-icon fas fa-sun";
    }
});

for (let element of content.children) {
    console.log(element.children[0]);
    element.children[1].addEventListener("click", () => {
        element.children[0].classList.toggle("checked");
    });
}
