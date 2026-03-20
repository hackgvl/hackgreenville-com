/**
 * Particle network canvas for the Labs hero.
 * Dots drift slowly; lines connect nearby dots; mouse proximity repels particles.
 * Respects prefers-reduced-motion (static dots, no animation).
 */
export function initHeroCanvas(canvas) {
  const ctx = canvas.getContext('2d');
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)',
  ).matches;

  const PARTICLE_COUNT = 60;
  const CONNECT_DISTANCE = 120;
  const MOUSE_RADIUS = 150;
  const MOUSE_FORCE = 0.02;
  const BASE_SPEED = 0.3;

  let width, height;
  let mouse = { x: -1000, y: -1000 };
  let particles = [];
  let animId;

  function resize() {
    const rect = canvas.parentElement.getBoundingClientRect();
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    width = rect.width;
    height = rect.height;
    canvas.width = width * dpr;
    canvas.height = height * dpr;
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function createParticles() {
    particles = [];
    for (let i = 0; i < PARTICLE_COUNT; i++) {
      particles.push({
        x: Math.random() * width,
        y: Math.random() * height,
        vx: (Math.random() - 0.5) * BASE_SPEED,
        vy: (Math.random() - 0.5) * BASE_SPEED,
        r: Math.random() * 1.5 + 1,
      });
    }
  }

  function draw() {
    ctx.clearRect(0, 0, width, height);

    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];

      if (!prefersReducedMotion) {
        // Mouse repulsion
        const dx = p.x - mouse.x;
        const dy = p.y - mouse.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < MOUSE_RADIUS && dist > 0) {
          const force = ((MOUSE_RADIUS - dist) / MOUSE_RADIUS) * MOUSE_FORCE;
          p.vx += (dx / dist) * force;
          p.vy += (dy / dist) * force;
        }

        // Dampen velocity
        p.vx *= 0.99;
        p.vy *= 0.99;

        // Move
        p.x += p.vx;
        p.y += p.vy;

        // Wrap edges
        if (p.x < 0) p.x = width;
        if (p.x > width) p.x = 0;
        if (p.y < 0) p.y = height;
        if (p.y > height) p.y = 0;
      }

      // Draw dot
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
      ctx.fill();

      // Draw connections
      for (let j = i + 1; j < particles.length; j++) {
        const q = particles[j];
        const dx = p.x - q.x;
        const dy = p.y - q.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < CONNECT_DISTANCE) {
          ctx.beginPath();
          ctx.moveTo(p.x, p.y);
          ctx.lineTo(q.x, q.y);
          ctx.strokeStyle = `rgba(255, 255, 255, ${
            0.15 * (1 - dist / CONNECT_DISTANCE)
          })`;
          ctx.lineWidth = 0.5;
          ctx.stroke();
        }
      }
    }

    if (!prefersReducedMotion) {
      animId = requestAnimationFrame(draw);
    }
  }

  function onMouseMove(e) {
    const rect = canvas.getBoundingClientRect();
    mouse.x = e.clientX - rect.left;
    mouse.y = e.clientY - rect.top;
  }

  function onMouseLeave() {
    mouse.x = -1000;
    mouse.y = -1000;
  }

  function init() {
    resize();
    createParticles();
    canvas.parentElement.addEventListener('mousemove', onMouseMove);
    canvas.parentElement.addEventListener('mouseleave', onMouseLeave);
    window.addEventListener('resize', resize);
    draw();
  }

  function destroy() {
    cancelAnimationFrame(animId);
    canvas.parentElement.removeEventListener('mousemove', onMouseMove);
    canvas.parentElement.removeEventListener('mouseleave', onMouseLeave);
    window.removeEventListener('resize', resize);
  }

  init();
  return destroy;
}
