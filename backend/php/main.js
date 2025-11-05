function main() {
    const logBut = document.getElementById("logBut");
    const signBut = document.getElementById("signBut");
    const inlogForm = document.getElementById("login");
    const aanmeldForm = document.getElementById("aanmelden");

    if (logBut && signBut && inlogForm && aanmeldForm) {

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
}
main();

const gegevensTrigger = document.getElementById("gegevens");
const changeInfoPanel = document.getElementById("changeinfo");

if (gegevensTrigger && changeInfoPanel) {
    gegevensTrigger.addEventListener("click", () => {
        changeInfoPanel.style.display = "flex";
    });
}

// Toast notification function
function showToast(message, type = 'error') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.textContent = message;
    toast.className = 'toast show ' + type;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}