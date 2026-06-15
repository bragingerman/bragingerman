// =============================================
// THEMES
// =============================================
const THEMES = [
  { bg:'#f69fdc', card:'#ff88be', top:'#ff9fc8', bottom:'#f077b0', color:'#fff', shadow:'rgba(200,80,140,0.3)', name:'Pink' },
  { bg:'#222', card:'#333', top:'#3a3a3a', bottom:'#222', color:'#fff', shadow:'rgba(0,0,0,0.5)', name:'Dark' },
  { bg:'#fff', card:'#f0f0f0', top:'#f8f8f8', bottom:'#e0e0e0', color:'#333', shadow:'rgba(0,0,0,0.15)', name:'White' },
];

let currentTheme = 0;

function applyTheme(idx) {
  const t = THEMES[idx];
  const r = document.documentElement.style;
  r.setProperty('--bg', t.bg);
  r.setProperty('--card-bg', t.card);
  r.setProperty('--card-top', t.top);
  r.setProperty('--card-bottom', t.bottom);
  r.setProperty('--digit-color', t.color);
  r.setProperty('--divider', 'rgba(0,0,0,0.15)');
  r.setProperty('--ui-bg', 'rgba(128,128,128,0.25)');
  r.setProperty('--ui-hover', 'rgba(128,128,128,0.45)');
  r.setProperty('--ui-text', t.color);
  r.setProperty('--shadow', t.shadow);
  currentTheme = idx;
  document.querySelectorAll('.theme-dot').forEach((d,i)=>{
    d.classList.toggle('selected', i===idx);
  });
}

function buildThemePicker() {
  const p = document.getElementById('themePicker');
  THEMES.forEach((t,i) => {
    const d = document.createElement('div');
    d.className = 'theme-dot' + (i===currentTheme?' selected':'');
    d.style.background = t.card;
    d.title = t.name;
    d.onclick = () => applyTheme(i);
    // dark border for light themes
    if(['#fff','#f0f0f0','#fff3cd'].includes(t.card)) d.style.border='3px solid rgba(0,0,0,0.2)';
    p.appendChild(d);
  });
}

// =============================================
// FLIP DIGIT SYSTEM
// =============================================
function buildDigit(el) {
  el.innerHTML = `
    <div class="static-top"><span>0</span></div>
    <div class="static-bottom"><span>0</span></div>
    <div class="flap"><span>0</span></div>
  `;
  el._current = 0;
}

function setDigit(el, val, animate) {
  val = parseInt(val);
  if (el._current === val && !animate) return;
  const st = el.querySelector('.static-top span');
  const sb = el.querySelector('.static-bottom span');
  const flap = el.querySelector('.flap');
  const fs = flap.querySelector('span');
  const current = el._current;

  // Only animate if element was already initialized with a real value
  if (animate && current !== val && el._initialized) {
    st.textContent = current;
    sb.textContent = current;
    fs.textContent = current;
    flap.classList.remove('animate');
    void flap.offsetWidth; // reflow
    flap.classList.add('animate');
    setTimeout(() => {
      st.textContent = val;
      sb.textContent = val;
      fs.textContent = val;
      flap.classList.remove('animate');
    }, 520);
  } else {
    // No animation on first load
    st.textContent = val;
    sb.textContent = val;
    fs.textContent = val;
  }
  el._current = val;
  el._initialized = true;
}

// =============================================
// CLOCK STATE
// =============================================
let is24h = true;
let prevH1=-1,prevH2=-1,prevM1=-1,prevM2=-1;

let dH1, dH2, dM1, dM2;
let labelAmPm, labelWeekday;

const WEEKDAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

function pad(n){ return String(n).padStart(2,'0'); }

function updateLabels(now) {
  // Update weekday (always shown)
  if (labelWeekday) {
    labelWeekday.textContent = WEEKDAYS[now.getDay()];
  }

  // Update AM/PM (only in 12h mode)
  if (labelAmPm) {
    if (!is24h) {
      const hours = now.getHours();
      labelAmPm.textContent = hours >= 12 ? 'PM' : 'AM';
      labelAmPm.style.display = 'block';
    } else {
      labelAmPm.style.display = 'none';
    }
  }
}

function updateDisplay(hh, mm) {
  const h1=Math.floor(hh/10), h2=hh%10;
  const m1=Math.floor(mm/10), m2=mm%10;
  setDigit(dH1, h1, h1!==prevH1); prevH1=h1;
  setDigit(dH2, h2, h2!==prevH2); prevH2=h2;
  setDigit(dM1, m1, m1!==prevM1); prevM1=m1;
  setDigit(dM2, m2, m2!==prevM2); prevM2=m2;
}

function tickClock() {
  const now = new Date();
  let hh = now.getHours();
  if(!is24h) hh = hh % 12 || 12;
  updateDisplay(hh, now.getMinutes());
  updateLabels(now);
}

function toggle24() {
  is24h = !is24h;
  document.getElementById('btn24').textContent = is24h ? '24h' : '12h';
  document.getElementById('btn24').classList.toggle('active', !is24h);
  prevH1=prevH2=-1;
  tickClock();
}

function goFullscreen() {
  const el = document.documentElement;
  if(!document.fullscreenElement) {
    (el.requestFullscreen||el.webkitRequestFullscreen||el.mozRequestFullScreen).call(el);
  } else {
    (document.exitFullscreen||document.webkitExitFullscreen||document.mozCancelFullScreen).call(document);
  }
}

// =============================================
// MAIN CLOCK LOOP
// =============================================
let clockTimer = null;
function startClock() {
  if(clockTimer) clearInterval(clockTimer);
  tickClock();
  clockTimer = setInterval(tickClock, 1000);
}

// =============================================
// INIT
// =============================================
function init() {
  console.log('Init started, readyState:', document.readyState);

  dH1 = document.getElementById('d-h1');
  dH2 = document.getElementById('d-h2');
  dM1 = document.getElementById('d-m1');
  dM2 = document.getElementById('d-m2');
  labelAmPm = document.getElementById('labelAmPm');
  labelWeekday = document.getElementById('labelWeekday');

  console.log('Elements:', {dH1, dH2, dM1, dM2, labelAmPm, labelWeekday});

  [dH1,dH2,dM1,dM2].forEach(buildDigit);

  buildThemePicker();
  applyTheme(currentTheme);

  console.log('About to start clock...');
  startClock();
  console.log('Clock started');
}

// Run when DOM is ready
console.log('Script loaded, readyState:', document.readyState);
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}