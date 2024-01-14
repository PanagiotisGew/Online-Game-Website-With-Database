'use strict';

/* Function to add event on multiple elements */
const addEventOnElements = function (elements, eventType, callback) {
    for (let i = 0, len = elements.length; i < len; i++) {
        elements[i].addEventListener(eventType, callback);
    }
}

/* Function to scroll to the bottom of the page */
const scrollToBottom = function () {
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
};

/* Mobile Navbar: Navbar will show after clicking the menu button */
const navbar = document.querySelector("[data-navbar]");
const navToggler = document.querySelector("[data-nav-toggler]");
const navLinks = document.querySelectorAll("[data-nav-link]");

const toggleNav = function () {
    navbar.classList.toggle("active");
    this.classList.toggle("active");
}

navToggler.addEventListener("click", toggleNav);

const navClose = () => {
    navbar.classList.remove("active");
    navToggler.classList.remove("active");
}

addEventOnElements(navLinks, "click", navClose);

/* Header and Back Top Button: Header and back top btn will be active after scrolling down to 100px of screen */
const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

const activeEl = function () {
    if (window.scrollY > 100) {
        header.classList.add("active");
        backTopBtn.classList.add("active");
    } else {
        header.classList.remove("active");
        backTopBtn.classList.remove("active");
    }
}

window.addEventListener("scroll", activeEl);

/* Button Hover Ripple Effect */
const buttons = document.querySelectorAll("[data-btn]");

const buttonHoverRipple = function (event) {
    this.style.setProperty("--top", `${event.offsetY}px`);
    this.style.setProperty("--left", `${event.offsetX}px`);
}

addEventOnElements(buttons, "mousemove", buttonHoverRipple);

/* Scroll Reveal */
const revealElements = document.querySelectorAll("[data-reveal]");

const revealElementOnScroll = function () {
    for (let i = 0, len = revealElements.length; i < len; i++) {
        const isElementInsideWindow = revealElements[i].getBoundingClientRect().top < window.innerHeight / 1.1;

        if (isElementInsideWindow) {
            revealElements[i].classList.add("revealed");
        }
    }
}

window.addEventListener("scroll", revealElementOnScroll);

window.addEventListener("load", revealElementOnScroll);

/* Smooth Scrolling for All Navigation Links */
addEventOnElements(navLinks, "click", function (event) {
    event.preventDefault();

    const targetSectionId = this.getAttribute("href").substring(1);
    const targetSection = document.getElementById(targetSectionId);

    if (targetSection) {
        targetSection.scrollIntoView({ behavior: "smooth" });
    }
});

/* Textarea Hover Ripple Effect */
const textareaField = document.querySelector(".textarea-field");

const textareaHoverRipple = function (event) {
    const rect = textareaField.getBoundingClientRect();
    const offsetX = event.clientX - rect.left;
    const offsetY = event.clientY - rect.top;

    textareaField.style.setProperty("--top", `${offsetY - 10}px`);
    textareaField.style.setProperty("--left", `${offsetX - 10}px`);
}

textareaField.addEventListener("click", textareaHoverRipple);

/* Additional Code for Character Form and Back to Top Button */
document.addEventListener('DOMContentLoaded', function () {
    const findCharacterForm = document.querySelector('#MyCharactersAndMissions form');
    const backToTopButton = document.querySelector('.back-top-btn');

    findCharacterForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const characterName = this.querySelector('input[name="character_name"]').value.trim();
        const targetSectionId = characterName.replace(/\s+/g, '-').toLowerCase();
        scrollToBottom(); // Scroll to the bottom of the page
    });

    backToTopButton.addEventListener('click', function () {
        document.body.scrollIntoView({
            behavior: 'smooth'
        });
    });
});

/* Add event listeners for the "Find" and "Delete Mission" buttons */
const findMissionButton = document.querySelector('.btn');
const deleteMissionButton = document.querySelector('.delete-btn');

if (findMissionButton) {
    findMissionButton.addEventListener('click', function () {
        // Add any other logic you need before scrolling (if necessary)
        scrollToBottom(); // Scroll to the bottom of the page
    });
}

if (deleteMissionButton) {
    deleteMissionButton.addEventListener('click', function () {
        // Add any other logic you need before scrolling (if necessary)
        scrollToBottom(); // Scroll to the bottom of the page
    });
}
