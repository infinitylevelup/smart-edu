// smooth scroll
window.scrollToSection = function(id){
  document.getElementById(id).scrollIntoView({behavior:'smooth'});
};

// -------------------------
// Pricing cycles
// -------------------------
let currentCycle = "monthly";

window.setCycle = function(cycle, btn){
  currentCycle = cycle;

  document.querySelectorAll(".cycle-selector .btn")
    .forEach(b=>b.classList.remove("active"));
  btn.classList.add("active");

  document.querySelectorAll(".price-val").forEach(el=>{
    const val = el.dataset[cycle];
    el.textContent = Number(val).toLocaleString("fa-IR");

    const label = el.closest(".pricing-card")
      .querySelector(".price-label");

    label.textContent =
      (cycle==="monthly") ? "ماهانه" :
      (cycle==="quarterly") ? "سه‌ماهه" : "سالیانه";
  });
};

// -------------------------
// Discount countdown (safe)
// -------------------------
const discountDeadline = new Date(Date.now() + 1000*60*60*24*3); // 3 days
const countdownEl = document.getElementById("discountCountdown");
const statusEl = document.getElementById("discountStatus");
function updateCountdown(){
  if(!countdownEl || !statusEl) return; // <<< guard

  const now = new Date();
  const diff = discountDeadline - now;

  if(diff <= 0){
    countdownEl.textContent = "00:00:00";
    statusEl.textContent = "پایان یافت";
    statusEl.className = "badge text-bg-secondary";
    return;
  }

  const totalSeconds = Math.floor(diff/1000);
  const hours = String(Math.floor(totalSeconds/3600)).padStart(2,"0");
  const minutes = String(Math.floor((totalSeconds%3600)/60)).padStart(2,"0");
  const seconds = String(totalSeconds%60).padStart(2,"0");
  countdownEl.textContent = `${hours}:${minutes}:${seconds}`;
}
updateCountdown();
setInterval(updateCountdown, 1000);

