(() => {
  const $ = (s, root = document) => root.querySelector(s);
  const root = document.documentElement;

  Object.entries(SITE_CONFIG.theme).forEach(([key, value]) => root.style.setProperty(`--${key.replace(/[A-Z]/g, m => '-' + m.toLowerCase())}`, value));

  $('#groomName').textContent = SITE_CONFIG.couple.groom;
  $('#brideName').textContent = SITE_CONFIG.couple.bride;
  $('#footerNames').textContent = SITE_CONFIG.couple.latin;
  $('#eventDate').textContent = SITE_CONFIG.event.dateLabel;
  $('#eventTime').textContent = SITE_CONFIG.event.timeLabel;
  $('#eventVenue').textContent = SITE_CONFIG.event.venue;
  $('#eventCity').textContent = SITE_CONFIG.event.city;
  $('#mapLink').href = SITE_CONFIG.event.mapUrl;

  const envelope = $('#envelope');
  $('#openInvite').addEventListener('click', () => {
    envelope.classList.add('is-opening');
    setTimeout(() => envelope.classList.add('opened'), 1250);
  });

  const target = new Date(SITE_CONFIG.event.date).getTime();
  const updateCountdown = () => {
    let diff = target - Date.now();
    if (diff < 0) diff = 0;
    const values = {
      days: Math.floor(diff / 86400000),
      hours: Math.floor(diff / 3600000) % 24,
      minutes: Math.floor(diff / 60000) % 60,
      seconds: Math.floor(diff / 1000) % 60
    };
    Object.entries(values).forEach(([unit, value]) => {
      const el = $(`[data-unit="${unit}"]`);
      el.textContent = String(value).padStart(2, '0');
    });
  };
  updateCountdown();
  setInterval(updateCountdown, 1000);

  const audio = SITE_CONFIG.music ? new Audio(SITE_CONFIG.music) : null;
  if (audio) {
    const btn = $('#musicBtn'); btn.hidden = false;
    btn.addEventListener('click', async () => {
      if (audio.paused) { await audio.play(); btn.innerHTML = '❚❚ <span>إيقاف الموسيقى</span>'; }
      else { audio.pause(); btn.innerHTML = '♪ <span>تشغيل الموسيقى</span>'; }
    });
  }

  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
  }), { threshold: .12 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  const form = $('#rsvpForm');
  const status = $('#formStatus');
  form.addEventListener('submit', async e => {
    e.preventDefault();
    status.textContent = 'جارٍ حفظ الرد...';
    try {
      const response = await fetch('api/rsvp.php', { method: 'POST', body: new FormData(form) });
      const data = await response.json();
      if (!response.ok || !data.success) throw new Error(data.message || 'حدث خطأ');
      status.textContent = 'تم تسجيل ردك بنجاح، شكرًا لمشاركتنا فرحتنا ♥';
      form.reset();
    } catch (err) {
      status.textContent = 'تعذر حفظ الرد. تأكد من تشغيل الموقع على PHP ثم حاول مرة أخرى.';
    }
  });
})();
