document.addEventListener("DOMContentLoaded", () => {
    // Nav bar et menu burger
    const nav = document.querySelector("nav");
    const menu = document.getElementById("menu");
    const expand_button = document.getElementById("expand-menu-btn");

    expand_button.addEventListener("click", function() {
        nav.classList.toggle("nav-expanded");
        menu.classList.toggle("menu-hidden");
    });

    // Section: Popular
    // Données récupérées avec l'API de rawg.io et reformatées dans le cadre de l'évaluation ECF1
    let popularGamesData = [
        {
            "name": "Grand Theft Auto V",
            "background_image": "./assets/img/gta-v.jpg",
            "rating": 4.47,
        },
        {
            "name": "The Witcher 3: Wild Hunt",
            "background_image": "./assets/img/tw-3.jpg",
            "rating": 4.66
        },
        {
            "name": "Portal 2",
            "background_image": "./assets/img/portal-2.jpg",
            "rating": 4.62
        },
        {
            "name": "Tomb Raider (2013)",
            "background_image": "./assets/img/tr-2013.jpg",
            "rating": 4.05
        },
        {
            "name": "Counter-Strike: Global Offensive",
            "background_image": "./assets/img/csgo.jpg",
            "rating": 3.56
        }
    ];

    let popular = document.querySelector(".popular-container");

    popularGamesData.forEach((game) => {
        // console.log(game);
        let item = document.createElement("div");
        item.classList.add("popular-container-item");

        let gameTitle = document.createElement("h3");
        
        let gameImage = document.createElement("img");
        gameImage.classList.add("game-img");
        
        let gameRating = document.createElement("p");

        gameTitle.innerHTML = game.name;
        gameImage.setAttribute("src", game.background_image);
        gameRating.innerHTML = `Note : ${game.rating}/5`;
        
        item.appendChild(gameTitle);
        item.appendChild(gameImage);
        item.appendChild(gameRating);
        
        popular.appendChild(item);
    });

    // Section: Contact et contrôle du formulaire
    let name = document.getElementById("name");
    let checkName = document.getElementById("checkName");
    let nameValid = false;
    // Contrôle du nom : pas de caractères chiffrés ou spéciaux, caractères accentués autorisés et espace autorisé
    let regexName = /^[A-zÀ-ú ]+$/;

    name.addEventListener("change", () => {
        if(name.value.length > 0) {
            if (!name.value.match(regexName)) {
                checkName.classList.add("error");
                checkName.innerHTML = "Le nom doit être composé uniquement de lettres, minuscules ou majuscules, accentués ou non.";
                nameValid = false;
            }
            else {
                checkName.classList.remove("error");
                checkName.innerHTML = "";
                nameValid = true;
            }
        }
        else {
            nameValid = false;
        }

        formValid();
    });

    let email = document.getElementById("email");
    let checkEmail = document.getElementById("checkEmail");
    // Contrôle de l'adresse e-mail : pas de caractères spéciaux, pas de caractères accentués, doit contenir un "@" et un "."
    let regexEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    let emailValid = false;

    email.addEventListener("change", () => {
        if(email.value.length > 0) {
            if (!email.value.match(regexEmail)) {
                checkEmail.classList.add("error");
                checkEmail.innerHTML = "Veuillez renseigner une adresse e-mail valide.";
                emailValid = false;
            }
            else {
                checkEmail.classList.remove("error");
                checkEmail.innerHTML = "";
                emailValid = true;
            }
        }
        else {
            emailValid = false;
        }

        formValid();
    });

    let message = document.getElementById("message");
    let checkMessage = document.getElementById("checkMessage");
    let messageValid = false;

    message.addEventListener("change", () => {
        if(message.value.length > 0) {
            checkMessage.classList.remove("error");
            checkMessage.innerHTML = "";
            messageValid = true;
        }
        else {
            checkMessage.classList.add("error");
            checkMessage.innerHTML = "Le message ne peut pas être vide.";
            messageValid = false;
        }

        formValid();
    });

    let formValid = () => {
        if (nameValid && emailValid && messageValid) {
            document.querySelector(`input[type="submit"]`).disabled = false;
            document.querySelector(`input[type="submit"]`).classList.remove("button-error");
            document.querySelector(`input[type="submit"]`).style.cursor = "pointer";
        }
        else {
            document.querySelector(`input[type="submit"]`).disabled = true;
            document.querySelector(`input[type="submit"]`).classList.add("button-error");
            document.querySelector(`input[type="submit"]`).style.cursor = "not-allowed";
        }
    }
});
