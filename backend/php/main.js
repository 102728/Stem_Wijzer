function main() {
    const logBut = document.getElementById("logBut");
    const signBut = document.getElementById("signBut");
    const inlogForm = document.getElementById("login");
    const aanmeldForm = document.getElementById("aanmelden");

    if (!logBut || !signBut || !inlogForm || !aanmeldForm) return;

    logBut.addEventListener("click", (e) => {
        e.preventDefault();
        inlogForm.style.display = "none";
        aanmeldForm.style.display = "block";
    }); 

    signBut.addEventListener("click", (e) => {
        e.preventDefault();
        aanmeldForm.style.display = "none";
        inlogForm.style.display = "block";
    });
}

main();