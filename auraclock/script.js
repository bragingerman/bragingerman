const clock = document.getElementById("clock");
const time = document.getElementById("time");
const amPm = document.getElementById("am-pm");
const switchBtn = document.getElementById("switch-btn");

let is24Hour = true;

function updateTime() {
  let date = new Date();
  let hours = date.getHours();
  let minutes = date.getMinutes();

  if (!is24Hour) {
    let amPmValue = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;
    amPm.innerText = amPmValue;
    time.innerText = `${hours}:${minutes < 10 ? "0" : ""}${minutes}`;
  } else {
    amPm.innerText = "";
    time.innerText = `${hours < 10 ? "0" : ""}${hours}:${minutes < 10 ? "0" : ""}${minutes}`;
  }
}

switchBtn.addEventListener("click", () => {
  is24Hour = !is24Hour;
  switchBtn.innerText = is24Hour ? "24" : "12";
});

setInterval(updateTime, 1000);