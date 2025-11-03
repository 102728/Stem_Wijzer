function main() {
    const logBut = document.getElementById("logBut");
    const signBut = document.getElementById("signBut");
    const inlogForm = document.getElementById("login");
    const aanmeldForm = document.getElementById("aanmelden");

    logBut.addEventListener("click", () => {
        inlogForm.style.display = "none";
        aanmeldForm.style.display = "flex";
    }); 

    signBut.addEventListener("click", () => {
        aanmeldForm.style.display = "none";
        inlogForm.style.display = "flex";
    })
}

main();