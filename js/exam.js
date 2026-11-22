
const timer = document.getElementById("timer");
const examForm = document.getElementById("examForm");

let remaining = Number(timer.dataset.remaining);

function updateTimer() {

    let minutes = Math.floor(remaining / 60);
    let seconds = remaining % 60;

    timer.textContent =
        String(minutes).padStart(2, "0") + ":" +
        String(seconds).padStart(2, "0");

    if (remaining <= 0) {
        clearInterval(countdown);

        alert("Time is over! Your examination will be submitted.");

        examForm.submit();
        return;
    }

    remaining--;
}

updateTimer();

const countdown = setInterval(updateTimer, 1000);
