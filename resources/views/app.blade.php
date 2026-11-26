@verbatim
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Si Tempi</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Quicksand:wght@600;700&display=swap" rel="stylesheet">
<style>
  :root { --bg:#F8F6E6; --green:#5E8C3A; --dark:#3F5A2E; --bubble:#F3E7A8; --sidebar-w:260px; }
  * { box-sizing:border-box; margin:0; padding:0; }
  html, body { height:100%; font-family:'Quicksand',system-ui,sans-serif; background:var(--bg); color:var(--dark); }

  .page { display:none; min-height:100vh; position:relative; overflow:hidden; }
  .page.active { display:block; }

  /* ===== SIDEBAR NAVIGASI REVISI ===== */
  .sidebar { width:var(--sidebar-w); background:#FFFDF2; border-right:2px solid #EAE6CE; display:flex; flex-direction:column; justify-content:space-between; padding:24px 16px; position:fixed; top:0; bottom:0; left:0; z-index:100; transform:translateX(-100%); transition:transform .3s ease; }
  .sidebar.show { transform:translateX(0); }
  .sb-brand { font-family:'Fredoka',sans-serif; font-size:26px; color:var(--green); display:flex; align-items:center; justify-content:space-between; padding:0 12px 20px; border-bottom:1px dashed #E3E9C9; }
  .sb-close { background:none; border:0; font-size:24px; cursor:pointer; color:var(--dark); }
  .sb-menu { list-style:none; margin-top:20px; display:flex; flex-direction:column; gap:6px; overflow-y:auto; max-height:calc(100vh - 180px); }
  .sb-item { font-weight:700; font-size:15px; color:#5A6B55; padding:12px 16px; border-radius:16px; cursor:pointer; display:flex; align-items:center; gap:12px; transition:.15s; }
  .sb-item:hover, .sb-item.active { background:linear-gradient(#6A9544,#4A7A2B); color:#fff; }
  .sb-sub { margin-left:36px; font-size:13px; font-weight:600; color:#7A8B75; display:flex; flex-direction:column; gap:6px; margin-top:4px; }
  .sb-sub span { cursor:pointer; padding:4px 8px; border-radius:8px; }
  .sb-sub span:hover { color:var(--green); background:#F1F4E4; }
  .sb-footer { border-top:1px dashed #E3E9C9; padding-top:16px; display:flex; flex-direction:column; gap:10px; }
  .btn-icon { background:none; border:0; font:700 15px 'Quicksand',sans-serif; color:#9A4B2B; cursor:pointer; display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:14px; }
  .btn-icon:hover { background:#FCE4D6; }

  /* ornamen latar */
  .leaf { position:absolute; pointer-events:none; }
  .l1 { top:-30px; left:-40px; width:240px; color:#8DB35A; transform:rotate(25deg); }
  .l2 { top:30px; right:5%; width:120px; color:#9DBF66; transform:rotate(-25deg); }
  .l3 { bottom:-60px; left:28%; width:220px; color:#B5CF7E; transform:rotate(160deg); }
  .l4 { bottom:-40px; right:-30px; width:230px; color:#8DB35A; transform:rotate(-120deg); }
  .l5 { top:42%; left:-50px; width:150px; color:#B5CF7E; transform:rotate(60deg); }
  .bean { position:absolute; width:var(--w,34px); transform:rotate(var(--r,0deg)); pointer-events:none; }

  nav { position:relative; z-index:2; display:flex; align-items:center; justify-content:space-between; padding:24px 6%; }
  .logo { font-family:'Fredoka',sans-serif; font-weight:700; font-size:28px; color:var(--green); }
  nav ul { display:flex; gap:32px; list-style:none; font-weight:700; }
  nav a { color:var(--dark); text-decoration:none; }
  nav a:hover { color:var(--green); }

  .hero { position:relative; z-index:1; display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr);
          align-items:center; gap:40px; padding:10px 6% 60px; min-height:calc(100vh - 90px); }
  h1 { font-family:'Fredoka',sans-serif; font-weight:700; font-size:clamp(64px,9vw,120px); line-height:1;
       color:var(--green); text-shadow:3px 4px 0 var(--dark); transform:rotate(-3deg); transform-origin:left center; white-space:nowrap; }

  .sub { margin-top:34px; width:fit-content; max-width:100%; font-weight:700;
         font-size:clamp(20px,2.2vw,30px); line-height:1.4; transform:rotate(-4deg); transform-origin:left center; }
  .sub span { display:block; white-space:nowrap; }
  .sub span:nth-child(2) { margin-left:2.4em; }
  .sub span:nth-child(3) { margin-left:4.6em; }

  .btn { margin-top:48px; margin-left:clamp(60px,9vw,150px); display:inline-flex; align-items:center; gap:14px; padding:18px 48px; border:0; border-radius:50px; cursor:pointer;
         background:var(--green); color:#fff; font:700 24px 'Quicksand',sans-serif;
         box-shadow:0 6px 14px rgba(94,140,58,.4); transition:transform .15s, box-shadow .15s; }
  .btn:hover { transform:translateY(-3px); box-shadow:0 10px 18px rgba(94,140,58,.45); }
  .btn:active { transform:scale(.97); }

  .visual { position:relative; padding-top:80px; }
  .scene { width:100%; max-width:660px; display:block; margin-left:auto; }
  .bubble { position:absolute; top:0; right:2%; width:180px; padding:22px 16px; text-align:center; font-weight:700; font-size:18px;
            background:#FDFBEF; border:3px solid var(--bubble); border-radius:60px; animation:bob 3s ease-in-out infinite; }
  @keyframes bob { 50% { transform:translateY(-8px); } }

  /* ===== Halaman login ===== */
  .login { background:radial-gradient(circle at 72% 55%, #FFF8DC 0, transparent 45%), radial-gradient(circle at 15% 70%, #FFF6D6 0, transparent 40%), #FBF0C8; }
  .login .wrap { position:relative; z-index:1; display:flex; flex-direction:column; align-items:center; min-height:100vh; padding:60px 20px 200px; }
  .ttl { position:relative; width:fit-content; margin-top:30px; text-align:center; font-family:'Fredoka',sans-serif; font-weight:700;
         font-size:clamp(38px,4vw,54px); line-height:1.15; color:#2F4F1E; text-shadow:0 0 6px #fff, 0 0 12px #fff, 0 0 18px #fff; }
  .sprout { position:absolute; width:56px; top:-48px; left:-8px; }
  .desc { margin-top:16px; text-align:center; font-weight:600; font-size:19px; line-height:1.5; color:#4A6340; max-width:300px; }
  .mini { width:260px; margin:18px 0 6px; }
  .form { width:100%; max-width:440px; }
  .field { display:flex; align-items:center; gap:16px; height:68px; padding:0 24px; margin-bottom:18px; background:#fff; border-radius:26px; box-shadow:0 4px 14px rgba(120,100,40,.14); }
  .field svg { flex:none; }
  .field input { flex:1; min-width:0; border:0; outline:0; background:transparent; font:600 18px 'Quicksand',sans-serif; color:#3F5A2E; }
  .field input::placeholder { color:#8A93A6; }
  .eye { background:none; border:0; cursor:pointer; display:flex; padding:0; }
  .msg { text-align:center; font-weight:700; font-size:15px; color:#9A4B2B; margin-bottom:12px; min-height:22px; }
  .masuk { width:100%; height:64px; border:0; border-radius:36px; cursor:pointer; color:#fff; font:700 24px 'Quicksand',sans-serif; text-shadow:0 2px 3px rgba(0,0,0,.4);
           background:linear-gradient(#6A9544,#4A7A2B); box-shadow:0 6px 14px rgba(74,122,43,.4); transition:transform .15s; }
  .masuk:hover { transform:translateY(-2px); }
  .masuk:active { transform:scale(.98); }
  .daftar { margin-top:26px; text-align:center; font-weight:600; font-size:17px; color:#5A6B55; }
  .daftar a { margin-left:10px; font-weight:700; color:#2F4F1E; text-decoration:none; cursor:pointer; }
  .daftar a:hover { text-decoration:underline; }
  .orn { position:absolute; bottom:0; width:clamp(220px,26vw,420px); pointer-events:none; }
  .orn-l { left:0; }
  .orn-r { right:0; transform:scaleX(-1); }

  /* ===== Dashboard ===== */
  .dash { background:#F8F6E6; color:#2F4F1E; }
  .dcol { max-width:640px; margin:0 auto; padding:48px 24px 150px; }
  .dhead { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; }
  .hi { font-weight:700; font-size:clamp(30px,4vw,38px); line-height:1.25; }
  .hi .wave { margin-left:10px; font-size:.85em; }
  .dsub { margin-top:12px; max-width:440px; font-weight:600; font-size:clamp(16px,2vw,19px); line-height:1.5; color:#3F5A2E; }
  .avatar { flex:none; width:clamp(92px,13vw,122px); aspect-ratio:1; border-radius:50%; overflow:hidden; background:#FFF3C4; border:5px solid #FFFBEA; box-shadow:0 4px 14px rgba(100,90,40,.2); }
  .avatar svg { width:100%; height:100%; display:block; }

  .warn-banner { margin-top:20px; padding:16px 20px; border-radius:22px; background:#FADBD8; border:2px solid #E8663D; color:#78281F; font-weight:700; font-size:15px; display:none; align-items:center; gap:12px; }
  .warn-banner.show { display:flex; }
  .warn-banner svg { flex:none; width:28px; height:28px; fill:#E8663D; }

  .stok { display:flex; align-items:center; gap:24px; margin-top:20px; padding:26px 24px; border-radius:30px;
          background:linear-gradient(135deg,#FDEFBE,#FBE7A6); box-shadow:0 4px 14px rgba(200,170,60,.2); }
  .stok > svg { flex:none; width:clamp(70px,14vw,96px); }
  .sinfo { flex:1; min-width:0; }
  .sinfo h3 { font-size:clamp(22px,3.2vw,28px); font-weight:700; }
  .track { height:22px; margin:12px 0; border-radius:12px; background:#D8DDB3; overflow:hidden; }
  .fill { height:100%; width:0; border-radius:12px; background:linear-gradient(#6B9A45,#4A7A2B); transition:width 1s ease; }
  .srow { display:flex; justify-content:space-between; align-items:baseline; font-weight:600; color:#4A6340; }
  .srow b { font-size:clamp(20px,2.8vw,25px); color:#2F4F1E; }
  .min-info { margin-top:6px; font-size:14px; font-weight:700; color:#8C6B1B; }

  .quick-btns { display:flex; gap:12px; margin-top:20px; }
  .btn-quick { flex:1; padding:14px 12px; border:0; border-radius:20px; cursor:pointer; font:700 15px 'Quicksand',sans-serif;
                display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 12px rgba(100,90,40,.12); transition:transform .15s; }
  .btn-quick:active { transform:scale(.97); }
  .bq-add { background:linear-gradient(#6A9544,#4A7A2B); color:#fff; }
  .bq-stok { background:#FFFDF2; color:#2F4F1E; border:1.5px solid #E3E9C9; }

  .sec-title { margin-top:28px; font-size:20px; font-weight:800; color:#1F2A18; }
  .status-summary { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:12px; }
  .sum-card { padding:16px 12px; border-radius:20px; background:#fff; text-align:center; box-shadow:0 3px 10px rgba(100,90,40,.08); }
  .sum-card div { font-size:26px; font-weight:800; color:#2F4F1E; }
  .sum-card span { font-size:13px; font-weight:700; color:#6B6F7A; }

  .info-widgets { display:flex; flex-direction:column; gap:14px; margin-top:20px; }
  .wcard { padding:18px 20px; border-radius:24px; background:#FFFEF8; border:1px solid #fff; box-shadow:0 3px 12px rgba(100,90,40,.1); display:flex; align-items:center; justify-content:space-between; }
  .wcard-info h4 { font-size:16px; font-weight:800; color:#1F2A18; }
  .wcard-info p { font-size:14px; font-weight:600; color:#5A6B55; margin-top:2px; }
  .wcard-badge { padding:8px 16px; border-radius:18px; font-weight:800; font-size:15px; }
  .wb-wait { background:#FDEFC4; color:#E8663D; }
  .wb-sched { background:#E2ECF7; color:#2B5B84; }

  .banner { position:relative; display:flex; align-items:center; gap:10px; margin-top:24px; padding:18px 24px 18px 12px; border-radius:28px;
            background:linear-gradient(135deg,#E8EFD2,#DDE8C0); }
  .banner > svg.mk { flex:none; width:clamp(120px,24vw,160px); margin:-34px 0 -14px; }
  .banner h4 { font-size:clamp(21px,3vw,26px); font-weight:700; }
  .banner p { margin-top:6px; font-weight:600; font-size:clamp(16px,2.2vw,20px); line-height:1.45; color:#3F5A2E; }
  .spark { position:absolute; left:clamp(130px,25vw,170px); top:-6px; }

  .bnav { position:fixed; left:0; right:0; bottom:0; z-index:5; background:#F8F6E6; box-shadow:0 -4px 14px rgba(0,0,0,.06); }
  .bnav .in { max-width:640px; margin:0 auto; display:flex; justify-content:space-around; padding:12px 8px 16px; }
  .nv { display:flex; flex-direction:column; align-items:center; gap:4px; min-width:80px; background:none; border:0; cursor:pointer;
        font:600 clamp(14px,2vw,17px) 'Quicksand',sans-serif; color:#46544A; }
  .nv svg { width:32px; height:32px; }
  .nv.on { color:#2F4F1E; font-weight:700; }
  .toast { position:fixed; left:50%; bottom:110px; transform:translate(-50%,20px); padding:12px 22px; border-radius:24px; background:#2F4F1E; color:#fff;
            font-weight:700; opacity:0; pointer-events:none; transition:.25s; z-index:30; max-width:90vw; text-align:center; }
  .toast.show { opacity:1; transform:translate(-50%,0); }
  .toast.err { background:#9A4B2B; }

  /* edit stok & modal styling */
  #stokCard { cursor:pointer; }
  .pen { width:18px; vertical-align:middle; margin-left:8px; opacity:.55; }
  .modal { position:fixed; inset:0; z-index:20; display:none; place-items:center; padding:20px; background:rgba(47,79,30,.35); }
  .modal.show { display:grid; }
  .mbox { width:100%; max-width:480px; padding:26px; border-radius:28px; background:#FFFDF2; box-shadow:0 10px 30px rgba(0,0,0,.2); color:#2F4F1E; max-height:90vh; overflow-y:auto; }
  .mbox h3 { font-size:24px; }
  .mbox p { margin-top:4px; font-weight:600; font-size:15px; color:#5A6B55; }
  .mbox label { display:block; margin-top:14px; font-weight:700; font-size:14px; }
  .mbox input, .mbox select { width:100%; height:48px; margin-top:6px; padding:0 16px; border:2px solid #E3E9C9; border-radius:16px; outline:0; background:#fff; font:700 16px 'Quicksand',sans-serif; color:#2F4F1E; }
  .mbox input:focus, .mbox select:focus { border-color:#6A9544; }
  .mrow { display:flex; gap:12px; margin-top:22px; }
  .mrow button { flex:1; height:50px; border:0; border-radius:26px; cursor:pointer; font:700 16px 'Quicksand',sans-serif; }
  .m-ok { background:#4A7A2B; color:#fff; }
  .m-no { background:#E8EBD4; color:#2F4F1E; }

  /* Style Tabel Riwayat */
  .tbl-riwayat { width:100%; border-collapse:collapse; margin-top:12px; font-size:13px; text-align:left; }
  .tbl-riwayat th, .tbl-riwayat td { padding:8px 10px; border-bottom:1px solid #E3E9C9; }
  .tbl-riwayat th { background:#EAEFD2; font-weight:800; color:#2F4F1E; }

  /* ===== Input Pesanan ===== */
  .ord { background:#F7F5E3; color:#1F2A18; }
  .owrap { position:relative; z-index:1; max-width:640px; min-height:100vh; margin:0 auto; padding:44px 24px 0; display:flex; flex-direction:column; }
  .ohead { display:flex; align-items:center; gap:22px; margin-bottom:22px; }
  .back { flex:none; width:44px; height:44px; display:grid; place-items:center; background:none; border:0; cursor:pointer; }
  .back svg { width:36px; }
  .ohead h2 { font-weight:700; font-size:clamp(28px,4vw,36px); color:#1F2A18; text-shadow:0 0 6px #fff, 0 0 10px #fff; }
  .ohead .lf { width:34px; }
  .lbl { display:block; margin:18px 0 10px 6px; font-weight:700; font-size:clamp(17px,2.2vw,20px); color:#1F2A18; text-shadow:0 0 5px #fff; }
  .ofield { position:relative; display:flex; align-items:center; gap:18px; height:78px; padding:0 24px; border:1.5px solid #fff; border-radius:22px;
            background:linear-gradient(#FFFEF8,#FAF9EE); box-shadow:0 3px 12px rgba(100,90,40,.14); }
  .ofield:focus-within { box-shadow:0 0 0 3px rgba(94,140,58,.35), 0 3px 12px rgba(100,90,40,.14); }
  .ofield svg { flex:none; width:34px; }
  .ofield svg.sm { width:28px; }
  .ofield input { flex:1; min-width:0; border:0; outline:0; background:transparent; font:700 clamp(16px,2.1vw,19px) 'Quicksand',sans-serif; color:#2F4F1E; }
  .ofield input::placeholder { color:#A9ABA0; }
  .ofield input[type=number] { -moz-appearance:textfield; }
  .ofield input[type=number]::-webkit-inner-spin-button, .ofield input[type=number]::-webkit-outer-spin-button { -webkit-appearance:none; margin:0; }
  .ofield .suf { font-weight:600; font-size:18px; color:#9A9C92; }
  
  .ofield .hid-date { position:absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer; z-index:5; }
  
  #tgl { cursor:pointer; }
  .simpan { margin-top:38px; height:76px; display:flex; align-items:center; justify-content:center; gap:22px; border:0; border-radius:40px; cursor:pointer;
            color:#fff; font:600 clamp(19px,2.6vw,24px) 'Quicksand',sans-serif; background:linear-gradient(#6A9544,#4A7A2B);
            box-shadow:0 6px 14px rgba(74,122,43,.4); transition:transform .15s; }
  .simpan:hover { transform:translateY(-2px); }
  .simpan:active { transform:scale(.98); }
  .simpan svg { width:38px; }
  .oscene { margin-top:auto; padding-top:24px; width:100%; display:block; }
  .oscene .bub { transform-box:fill-box; transform-origin:center; }
  .oscene .bub.pop { animation:pop .6s ease; }
  @keyframes pop { 40% { transform:scale(1.12) rotate(-3deg); } }
  .hill { position:absolute; left:-10%; right:-10%; bottom:0; height:80px; z-index:0; background:linear-gradient(#DDE9B8,#CFE0A0); border-radius:50% 50% 0 0 / 100% 100% 0 0; }
  .pl { position:absolute; bottom:0; z-index:0; width:clamp(120px,16vw,230px); }
  .pl-l { left:2%; } .pl-r { right:2%; transform:scaleX(-1); }

  /* navigasi bawah */
  .bnav { display:none; }
  body.has-nav .bnav { display:block; }
  .nv:nth-child(1) svg { fill:none; stroke:#46544A; stroke-width:2.5; stroke-linejoin:round; }
  .nv.on:nth-child(1) svg { fill:#3F6B2A; stroke:none; }
  .nv.on:nth-child(2) svg rect { fill:#3F6B2A; }
  .nv.on:nth-child(2) svg path { stroke:#fff; }
  .nv.on:nth-child(3) svg path { stroke:#3F6B2A; fill:#3F6B2A; }

  /* ===== Daftar Pesanan ===== */
  .lst { background:#F8F6E6; color:#2F4F1E; }
  .lcol { max-width:640px; margin:0 auto; padding:48px 24px 150px; }
  .lhead { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-bottom:18px; }
  .lhead-title { display:flex; align-items:center; gap:14px; }
  .lhead h2 { font-weight:700; font-size:clamp(30px,4vw,38px); color:#1F2A18; text-shadow:0 0 6px #fff; }
  .lhead svg { width:34px; }
  
  .btn-add-order { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:0; border-radius:24px;
                    background:linear-gradient(#6A9544,#4A7A2B); color:#fff; font:700 15px 'Quicksand',sans-serif; cursor:pointer;
                    box-shadow:0 4px 10px rgba(74,122,43,.3); transition:transform .15s; }
  .btn-add-order:hover { transform:translateY(-2px); }

  .search-box { margin-bottom:14px; }
  .search-field { display:flex; align-items:center; gap:12px; height:52px; padding:0 18px; background:#fff; border-radius:20px; box-shadow:0 3px 10px rgba(100,90,40,.1); }
  .search-field input { flex:1; border:0; outline:0; font:700 16px 'Quicksand',sans-serif; color:#2F4F1E; }

  .pills { display:flex; gap:10px; margin-bottom:20px; overflow-x:auto; padding:4px 2px 8px; }
  .pill { flex:none; padding:10px 20px; border:0; border-radius:28px; cursor:pointer; background:#F1F0DB; color:#3F5A2E;
          font:700 clamp(14px,1.8vw,16px) 'Quicksand',sans-serif; box-shadow:0 3px 8px rgba(100,90,40,.12); }
  .pill.on { background:linear-gradient(#5E8C3A,#4A7A2B); color:#fff; box-shadow:0 4px 10px rgba(74,122,43,.35); }

  .oi { display:flex; flex-direction:column; gap:12px; margin-bottom:18px; padding:18px 20px; border:1px solid #fff; border-radius:24px;
        background:linear-gradient(#FFFEF8,#FBFAF0); box-shadow:0 3px 12px rgba(100,90,40,.12); }
  .oi-head { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:1px solid #EEF0DC; padding-bottom:10px; }
  .oi-name { font-size:20px; font-weight:800; color:#1F2A18; }
  .oi-id { font-size:14px; font-weight:700; color:#8A93A6; }
  
  .oi-body { display:grid; grid-template-columns:1fr 1fr; gap:8px 16px; font-size:14px; font-weight:600; color:#4A6340; }
  .oi-body div span { display:block; font-size:12px; color:#8A93A6; font-weight:700; text-transform:uppercase; }
  .oi-body div b { font-size:15px; color:#1F2A18; }

  .oi-status-row { display:flex; gap:8px; align-items:center; margin-top:4px; flex-wrap:wrap; }
  .badge { padding:6px 14px; border-radius:16px; font-weight:700; font-size:13px; display:inline-flex; align-items:center; gap:4px; }
  .b-w { background:#FDEFC4; color:#E8663D; } 
  .b-d { background:#E2ECF7; color:#2B5B84; }
  .b-p { background:#DCE8C1; color:#4A6340; } 
  .b-s { background:#93B08A; color:#3F5A2E; }
  .b-app-true { background:#D5EAD8; color:#2E6B38; }
  .b-app-false { background:#FCE4D6; color:#C04B22; }

  .oi-actions { display:flex; gap:8px; justify-content:flex-end; margin-top:8px; border-top:1px dashed #EEF0DC; padding-top:10px; }
  .btn-act { padding:8px 16px; border:0; border-radius:16px; font:700 14px 'Quicksand',sans-serif; cursor:pointer; transition:transform .1s; }
  .btn-act:active { transform:scale(.95); }
  .btn-edit { background:#E8EBD4; color:#2F4F1E; }
  .btn-cancel { background:#F8E3D9; color:#9A4B2B; }
  .btn-flow { background:#4A7A2B; color:#fff; margin-left:auto; }

  .kosong { padding:40px 0; text-align:center; font-weight:700; color:#6B6F7A; }
  .drow { display:flex; justify-content:space-between; gap:12px; padding:9px 0; border-bottom:1px solid #EEF0DC; font-weight:600; }
  .drow span:first-child { color:#6B6F7A; }
  .drow span:last-child { font-weight:700; text-align:right; }
  .dwarn { margin-top:12px; padding:10px 14px; border-radius:14px; background:#F8E3D9; color:#9A4B2B; font-weight:700; font-size:15px; }

  /* ===== HALAMAN LAPORAN TEMA UI CARD ===== */
  .lap-container { max-width:640px; margin:0 auto; padding:48px 24px 150px; color:#2F4F1E; }
  .lap-header { display:flex; align-items:center; gap:16px; margin-bottom:16px; }
  .lap-header button { background:none; border:0; font-size:28px; cursor:pointer; color:#1F2A18; }
  .lap-header h2 { font-family:'Fredoka',sans-serif; font-size:32px; font-weight:700; color:#1F2A18; }

  .lap-tujuan-box { background:#EAEFD2; border-radius:18px; padding:16px 20px; margin-bottom:20px; font-size:14px; font-weight:600; line-height:1.5; color:#3F5A2E; border:1px solid #D8E0B5; }
  .lap-tujuan-box b { color:#1F2A18; font-weight:800; }
  
  .lap-tabs { display:flex; gap:10px; margin-bottom:24px; }
  .lap-tab { flex:1; padding:12px; border:0; border-radius:28px; background:#EAEFD2; color:#5A6B55; font:700 16px 'Quicksand',sans-serif; cursor:pointer; text-align:center; transition:.2s; }
  .lap-tab.active { background:#5E8C3A; color:#fff; box-shadow:0 4px 12px rgba(94,140,58,.3); }

  .lap-periode { margin-bottom:20px; }
  .lap-periode label { display:block; font-weight:700; font-size:15px; margin-bottom:8px; color:#3F5A2E; }
  .lap-periode-box { display:flex; align-items:center; gap:12px; background:#fff; padding:14px 20px; border-radius:22px; border:1px solid #E3E9C9; box-shadow:0 2px 8px rgba(100,90,40,.06); }
  .lap-periode-box select, .lap-periode-box input { border:0; outline:0; background:transparent; font:700 16px 'Quicksand',sans-serif; color:#2F4F1E; width:100%; cursor:pointer; }

  .lap-view-section { display:none; }
  .lap-view-section.active { display:block; }

  .lap-card-item { background:#FFFEF8; border-radius:26px; padding:20px 24px; margin-bottom:16px; border:1px solid #fff; box-shadow:0 4px 14px rgba(100,90,40,.08); display:flex; align-items:center; gap:20px; }
  .lap-card-icon { width:64px; height:64px; border-radius:50%; background:#FDEFBE; display:flex; align-items:center; justify-content:center; font-size:28px; flex:none; }
  .lap-card-val { font-size:26px; font-weight:800; color:#1F2A18; margin-top:2px; }
  .lap-card-lbl { font-size:15px; font-weight:700; color:#6B6F7A; }

  .lap-progress-card { background:#FFFEF8; border-radius:26px; padding:20px 24px; margin-bottom:28px; border:1px solid #fff; box-shadow:0 4px 14px rgba(100,90,40,.08); }
  .lap-progress-head { display:flex; align-items:center; gap:16px; margin-bottom:14px; }
  .lap-progress-bar { height:18px; background:#E3E9C9; border-radius:10px; overflow:hidden; }
  .lap-progress-fill { height:100%; background:#5E8C3A; border-radius:10px; width:94%; transition:width 1s ease; }

  .btn-lap-main { width:100%; height:64px; border:0; border-radius:36px; cursor:pointer; color:#fff; font:700 20px 'Quicksand',sans-serif; background:linear-gradient(#6A9544,#4A7A2B); box-shadow:0 6px 14px rgba(74,122,43,.4); display:flex; align-items:center; justify-content:center; gap:12px; transition:transform .15s; margin-top:12px; }
  .btn-lap-main:hover { transform:translateY(-2px); }

  @media (max-width:860px) {
    nav ul { display:none; }
    .hero { grid-template-columns:minmax(0,1fr); text-align:center; padding-top:0; }
    h1 { transform-origin:center; }
    .sub { margin-inline:auto; text-align:left; transform-origin:center; }
    .scene { margin:0 auto; }
    .btn { margin-left:0; }
  }
</style>
</head>
<body>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="leaf" viewBox="0 0 100 100">
      <path d="M8 92C6 42 42 8 94 6C96 58 60 94 8 92Z" fill="currentColor"/>
      <path d="M12 88L82 18" stroke="#fff" stroke-opacity=".35" stroke-width="3" fill="none" stroke-linecap="round"/>
    </symbol>
    <symbol id="bean" viewBox="0 0 40 28">
      <ellipse cx="20" cy="14" rx="18" ry="12" fill="#F6D77E" stroke="#D9AE4E" stroke-width="2.5"/>
      <path d="M9 9Q16 5 24 7" stroke="#fff" stroke-opacity=".7" stroke-width="2.5" fill="none" stroke-linecap="round"/>
    </symbol>
    <symbol id="mk" viewBox="0 0 200 220">
      <ellipse cx="78" cy="206" rx="17" ry="9" fill="#6B3F1D"/>
      <ellipse cx="124" cy="206" rx="17" ry="9" fill="#6B3F1D"/>
      <path d="M40 140C26 150 24 166 34 174" stroke="#6B3F1D" stroke-width="12" stroke-linecap="round" fill="none"/>
      <path d="M160 130C178 118 184 100 178 84" stroke="#6B3F1D" stroke-width="12" stroke-linecap="round" fill="none"/>
      <circle cx="100" cy="125" r="68" fill="#F8D980" stroke="#E0B050" stroke-width="3"/>
      <path d="M80 68C72 38 100 18 142 14C144 48 120 70 80 68Z" fill="#6E9A45" stroke="#4F7A2E" stroke-width="2.500"/>
      <path d="M86 64L128 28" stroke="#4F7A2E" stroke-width="2" fill="none" opacity=".6"/>
      <ellipse cx="78" cy="120" rx="8" ry="11" fill="#3B2314"/>
      <ellipse cx="122" cy="114" rx="8" ry="11" fill="#3B2314"/>
      <circle cx="80" cy="116" r="3" fill="#fff"/><circle cx="124" cy="110" r="3" fill="#fff"/>
      <ellipse cx="60" cy="142" rx="13" ry="9" fill="#F2A58E" opacity=".8"/>
      <ellipse cx="142" cy="136" rx="13" ry="9" fill="#F2A58E" opacity=".8"/>
      <path d="M86 142Q102 168 126 138Z" fill="#3B2314"/>
      <path d="M94 156Q105 164 118 152Q106 148 94 156Z" fill="#E8776F"/>
    </symbol>
    <symbol id="plant" viewBox="0 0 160 200">
      <path d="M60 200Q62 150 70 100" stroke="#6E9A45" stroke-width="4" fill="none" stroke-linecap="round"/>
      <use href="#leaf" x="10" y="40" width="90" height="90" style="color:#8DB35A" transform="rotate(-30 55 85)"/>
      <use href="#leaf" x="50" y="70" width="90" height="90" style="color:#A9C873" transform="rotate(40 95 115)"/>
      <use href="#leaf" x="-20" y="110" width="80" height="80" style="color:#6E9A45" transform="rotate(-70 20 150)"/>
    </symbol>
    <symbol id="orn" viewBox="0 0 300 200">
      <path d="M70 200Q75 150 90 90M150 200Q150 160 175 120" stroke="#5E8C3A" stroke-width="4" fill="none" stroke-linecap="round"/>
      <use href="#leaf" x="20" y="30" width="110" height="110" style="color:#7BA84C" transform="rotate(-25 75 85)"/>
      <use href="#leaf" x="85" y="0" width="100" height="100" style="color:#8DB35A" transform="rotate(35 135 50)"/>
      <use href="#leaf" x="-10" y="110" width="100" height="100" style="color:#6E9A45" transform="rotate(-65 40 160)"/>
      <use href="#leaf" x="120" y="90" width="110" height="110" style="color:#A9C873" transform="rotate(75 175 145)"/>
      <use href="#leaf" x="190" y="130" width="90" height="90" style="color:#8DB35A" transform="rotate(20 235 175)"/>
      <use href="#bean" x="88" y="172" width="44" height="31" transform="rotate(-10 110 188)"/>
      <use href="#bean" x="128" y="160" width="44" height="31"/>
      <use href="#bean" x="150" y="178" width="44" height="31" transform="rotate(12 172 194)"/>
      <use href="#bean" x="186" y="164" width="44" height="31" transform="rotate(-8 208 180)"/>
      <use href="#bean" x="112" y="140" width="40" height="28" transform="rotate(15 132 154)"/>
      <use href="#bean" x="160" y="140" width="40" height="28"/>
    </symbol>
  </defs>
</svg>

<!-- ===== SIDEBAR NAVIGASI REVISI ===== -->
<aside class="sidebar" id="sidebar">
  <div>
    <div class="sb-brand">
      🌱 Si Tempi
      <button class="sb-close" id="sbClose">✕</button>
    </div>
    <ul class="sb-menu">
      <li class="sb-item active" onclick="show(dash)">🏠 Beranda</li>
      <li class="sb-item" onclick="show(list)">📦 Pesanan</li>
      <div class="sb-sub">
        <span onclick="show(list)">• Daftar Pesanan</span>
        <span onclick="goList('Menunggu')">• Persetujuan Pesanan</span>
      </div>
      <li class="sb-item" onclick="show(list)">⚙️ Produksi</li>
      <div class="sb-sub">
        <span onclick="openJadwalModal()">• Jadwal Produksi</span>
        <span onclick="openHarianModal()">• Produksi Harian</span>
        <span onclick="goList('Diproduksi')">• Status Produksi</span>
      </div>
      <li class="sb-item" onclick="openPenerimaanModal()">🌾 Persediaan</li>
      <div class="sb-sub">
        <span onclick="openPenerimaanModal()">• Stok Kedelai</span>
        <span onclick="openPenerimaanModal()">• Penerimaan / Pembelian</span>
        <span onclick="openPenggunaanModal()">• Penggunaan Kedelai</span>
        <span onclick="aturStok()">• Atur Stok & Produksi Harian</span>
        <span onclick="resetData()" style="color:#B3401F">• Reset Data (mulai dari 0)</span>
      </div>
      <li class="sb-item" onclick="show(laporanPage)">📊 Laporan Mingguan</li>
    </ul>
  </div>

  <div class="sb-footer">
    <button class="btn-icon" onclick="openNotifModal()">🔔 Lonceng Notifikasi (<span id="notifBadgeCnt">1</span>)</button>
    <button class="btn-icon" onclick="logout()">🚪 Keluar</button>
  </div>
</aside>

<!-- ===== Halaman 1: Splash ===== -->
<section class="page active" id="splash">
  <svg class="leaf l1" viewBox="0 0 100 100"><use href="#leaf"/></svg>
  <svg class="leaf l2" viewBox="0 0 100 100"><use href="#leaf"/></svg>
  <svg class="leaf l3" viewBox="0 0 100 100"><use href="#leaf"/></svg>
  <svg class="leaf l4" viewBox="0 0 100 100"><use href="#leaf"/></svg>
  <svg class="leaf l5" viewBox="0 0 100 100"><use href="#leaf"/></svg>

  <svg class="bean" viewBox="0 0 40 28" style="top:16%;left:42%;--w:36px;--r:20deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:30%;left:4%;--w:30px;--r:-30deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:62%;left:3%;--w:38px;--r:40deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:80%;left:10%;--w:30px;--r:-10deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:88%;left:44%;--w:34px;--r:25deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:12%;right:3%;--w:32px;--r:-35deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:55%;right:2%;--w:36px;--r:15deg"><use href="#bean" width="40" height="28"/></svg>
  <svg class="bean" viewBox="0 0 40 28" style="top:86%;right:12%;--w:30px;--r:-20deg"><use href="#bean" width="40" height="28"/></svg>

  <nav>
    <div class="logo">Si Tempi</div>
    <ul>
      <li><a href="#">Beranda</a></li>
      <li><a href="#">Fitur</a></li>
      <li><a href="#">Tentang</a></li>
      <li><a href="#">Kontak</a></li>
    </ul>
  </nav>

  <main class="hero">
    <div>
      <h1>Si Tempi</h1>
      <p class="sub">
        <span>Teman Setia Produksi Tempe</span>
        <span>dari Kedelai Terbaik</span>
        <span>dari Kedelai Terbaik</span>
      </p>
      <button class="btn" id="mulai">Mulai
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </div>

    <div class="visual">
      <div class="bubble">Siap membantu produksi tempe bersama!</div>

      <svg class="scene" viewBox="0 0 560 400" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <g id="tp">
            <path d="M0 34L74 4L138 26L64 62Z" fill="#FCF3D6" stroke="#E3CF93" stroke-width="3" stroke-linejoin="round"/>
            <path d="M0 34L64 62L64 100L0 70Z" fill="#F0DDA3" stroke="#E3CF93" stroke-width="3" stroke-linejoin="round"/>
            <path d="M64 62L138 26L138 64L64 100Z" fill="#E6CE88" stroke="#E3CF93" stroke-width="3" stroke-linejoin="round"/>
            <g fill="#E9D090">
              <circle cx="50" cy="30" r="5"/><circle cx="78" cy="14" r="5"/><circle cx="104" cy="26" r="5"/><circle cx="72" cy="38" r="5"/><circle cx="90" cy="30" r="4"/>
              <circle cx="16" cy="56" r="4"/><circle cx="38" cy="72" r="4"/><circle cx="50" cy="62" r="4"/>
              <circle cx="90" cy="62" r="4"/><circle cx="118" cy="44" r="4"/><circle cx="104" cy="70" r="4"/>
            </g>
          </g>
        </defs>

        <circle cx="300" cy="210" r="190" fill="#FBF3C9" opacity=".6"/>

        <use href="#leaf" x="0" y="100" width="100" height="100" style="color:#9DBF66" transform="rotate(-15 50 150)"/>
        <use href="#leaf" x="-10" y="220" width="120" height="120" style="color:#8DB35A" transform="rotate(30 50 280)"/>
        <use href="#leaf" x="440" y="90" width="100" height="100" style="color:#A9C873" transform="rotate(20 490 140)"/>
        <use href="#leaf" x="370" y="20" width="70" height="70" style="color:#8DB35A" transform="rotate(-30 405 55)"/>
        <use href="#leaf" x="60" y="20" width="60" height="60" style="color:#B5CF7E" transform="rotate(10 90 50)"/>

        <g stroke="#9DBF66" stroke-width="6" stroke-linecap="round">
          <path d="M318 84L326 64"/><path d="M342 98L358 82"/><path d="M352 126L372 122"/>
        </g>

        <ellipse cx="175" cy="322" rx="125" ry="14" fill="#E3DCB4" opacity=".7"/>

        <path d="M150 335Q330 255 545 275L552 365Q340 398 170 378Z" fill="#6E9A45"/>
        <path d="M165 355Q350 322 545 318" stroke="#4F7A2E" stroke-width="3" fill="none"/>
        <g stroke="#86B055" stroke-width="2" fill="none" opacity=".8">
          <path d="M230 345L250 320M300 340L325 312M380 335L405 308M455 330L478 305"/>
        </g>

        <use href="#tp" transform="translate(288 222)"/>
        <use href="#tp" transform="translate(318 282) scale(.85)"/>
        <use href="#tp" transform="translate(405 250)"/>

        <use href="#bean" x="215" y="335" width="30" height="21" transform="rotate(-15 230 345)"/>
        <use href="#bean" x="250" y="350" width="26" height="18"/>
        <use href="#bean" x="500" y="345" width="28" height="20" transform="rotate(20 514 355)"/>
        <use href="#bean" x="525" y="300" width="24" height="17"/>
        <use href="#bean" x="395" y="355" width="24" height="17" transform="rotate(-25 407 363)"/>

        <path d="M14 340L130 340L116 392L30 392Z" fill="#C99A4B" stroke="#A5762F" stroke-width="3" stroke-linejoin="round"/>
        <path d="M22 358L124 358M26 374L120 374" stroke="#A5762F" stroke-width="2.5"/>
        <use href="#bean" x="18" y="322" width="34" height="24"/>
        <use href="#bean" x="46" y="314" width="34" height="24" transform="rotate(-10 63 326)"/>
        <use href="#bean" x="76" y="320" width="34" height="24"/>
        <use href="#bean" x="98" y="326" width="30" height="21" transform="rotate(15 113 336)"/>
        <use href="#bean" x="32" y="302" width="32" height="22" transform="rotate(10 48 313)"/>
        <use href="#bean" x="64" y="298" width="32" height="22"/>

        <ellipse cx="135" cy="308" rx="26" ry="13" fill="#6B3F1D"/>
        <ellipse cx="212" cy="308" rx="26" ry="13" fill="#6B3F1D"/>

        <path d="M258 195C292 180 302 148 294 112" stroke="#6B3F1D" stroke-width="26" stroke-linecap="round" fill="none"/>

        <circle cx="175" cy="205" r="98" fill="#F8D980" stroke="#E0B050" stroke-width="4"/>

        <path d="M148 118C138 68 185 32 255 26C256 86 208 122 148 118Z" fill="#7BA84C" stroke="#4F7A2E" stroke-width="3"/>
        <path d="M156 112L238 44" stroke="#4F7A2E" stroke-width="3" fill="none" stroke-linecap="round" opacity=".6"/>

        <path d="M118 168Q134 156 150 166" stroke="#7A4A1E" stroke-width="3.5" fill="none" stroke-linecap="round"/>
        <path d="M196 158Q212 146 228 156" stroke="#7A4A1E" stroke-width="3.5" fill="none" stroke-linecap="round"/>
        <ellipse cx="135" cy="200" rx="13" ry="17" fill="#3B2314"/>
        <ellipse cx="212" cy="190" rx="13" ry="17" fill="#3B2314"/>
        <circle cx="139" cy="193" r="5" fill="#fff"/>
        <circle cx="216" cy="183" r="5" fill="#fff"/>
        <ellipse cx="100" cy="232" rx="21" ry="14" fill="#F2A58E" opacity=".8"/>
        <ellipse cx="247" cy="222" rx="21" ry="14" fill="#F2A58E" opacity=".8"/>
        <path d="M130 236Q172 290 222 232Z" fill="#3B2314"/>
        <path d="M146 262Q175 276 202 256Q175 248 146 262Z" fill="#E8776F"/>

        <use href="#bean" x="238" y="282" width="38" height="27" transform="rotate(-15 257 295)"/>
      </svg>
    </div>
  </main>
</section>

<!-- ===== Halaman 2: Login ===== -->
<section class="page login" id="login">
  <svg class="orn orn-l" viewBox="0 0 300 200"><use href="#orn" width="300" height="200"/></svg>
  <svg class="orn orn-r" viewBox="0 0 300 200"><use href="#orn" width="300" height="200"/></svg>

  <div class="wrap">
    <h2 class="ttl">
      <svg class="sprout" viewBox="0 0 60 60"><use href="#leaf" x="0" y="14" width="34" height="34" style="color:#6E9A45" transform="rotate(-30 17 31)"/><use href="#leaf" x="24" y="8" width="34" height="34" style="color:#8DB35A" transform="rotate(25 41 25)"/></svg>
      Selamat Datang<br>di Si Tempi
    </h2>
    <p class="desc">Kelola produksi tempe dan bahan baku dengan mudah.</p>

    <svg class="mini" viewBox="0 0 260 200" xmlns="http://www.w3.org/2000/svg">
      <path transform="translate(205 30)" d="M0 8C-14 -6 -22 -16 -12 -22C-6 -26 0 -20 0 -16C0 -20 6 -26 12 -22C22 -16 14 -6 0 8Z" fill="#F4A261"/>
      <path transform="translate(40 138) scale(.9)" d="M0 8C-14 -6 -22 -16 -12 -22C-6 -26 0 -20 0 -16C0 -20 6 -26 12 -22C22 -16 14 -6 0 8Z" fill="#6E9A45"/>
      <use href="#leaf" x="222" y="62" width="22" height="22" style="color:#8DB35A" transform="rotate(30 233 73)"/>
      <use href="#leaf" x="30" y="60" width="18" height="18" style="color:#9DBF66" transform="rotate(-30 39 69)"/>
      <use href="#leaf" x="62" y="62" width="40" height="40" style="color:#6E9A45" transform="rotate(-50 82 82)"/>
      <ellipse cx="112" cy="174" rx="20" ry="11" fill="#6B3F1D"/>
      <ellipse cx="152" cy="174" rx="20" ry="11" fill="#6B3F1D"/>
      <path d="M180 112C198 102 206 86 201 66" stroke="#6B3F1D" stroke-width="14" stroke-linecap="round" fill="none"/>
      <circle cx="130" cy="112" r="66" fill="#F8D980" stroke="#E0B050" stroke-width="3"/>
      <path d="M108 60C100 30 128 10 172 6C174 40 148 62 108 60Z" fill="#6E9A45" stroke="#4F7A2E" stroke-width="2.5"/>
      <path d="M114 56L160 20" stroke="#4F7A2E" stroke-width="2" fill="none" opacity=".6"/>
      <ellipse cx="110" cy="108" rx="8" ry="11" fill="#3B2314"/>
      <ellipse cx="153" cy="102" rx="8" ry="11" fill="#3B2314"/>
      <circle cx="112" cy="104" r="3" fill="#fff"/><circle cx="155" cy="98" r="3" fill="#fff"/>
      <ellipse cx="92" cy="130" rx="13" ry="9" fill="#F2A58E" opacity=".8"/>
      <ellipse cx="172" cy="124" rx="13" ry="9" fill="#F2A58E" opacity=".8"/>
      <path d="M116 130Q133 156 157 126Z" fill="#3B2314"/>
      <path d="M124 144Q135 152 148 140Q136 136 124 144Z" fill="#E8776F"/>
    </svg>

    <div class="form">
      <label class="field">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#2B3A2B" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.600 8 7z"/></svg>
        <input type="text" id="user" placeholder="Email / Username" autocomplete="username">
      </label>
      <label class="field">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#2B3A2B" stroke-width="1.8" stroke-linecap="round"><rect x="4.500" y="10.500" width="15" height="10" rx="2.500"/><path d="M8 10.500V8a4 4 0 0 1 8 0v2.500"/><circle cx="12" cy="15.500" r="1" fill="#2B3A2B"/></svg>
        <input type="password" id="pw" placeholder="Password" autocomplete="current-password">
        <button type="button" class="eye" id="eye" aria-label="Tampilkan password">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#5B6B73" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.600-7 10-7 10 7 10 7-3.600 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </label>
      <p class="msg" id="msg"></p>
      <button class="masuk" id="masuk">Masuk</button>
      <p class="daftar">Belum punya akun? <a id="btnShowRegister">Daftar</a></p>
    </div>
  </div>
</section>

<!-- ===== Halaman 3: Dashboard / Beranda ===== -->
<section class="page dash" id="dash">
  <div class="dcol">
    <header class="dhead">
      <div>
        <button id="sbToggle" style="background:none; border:0; font-size:24px; cursor:pointer; margin-bottom:8px">☰ Menu</button>
        <div class="hi">Halo,<br><span id="displayUserName">Pengguna</span> <span class="wave">👋</span></div>
        <p class="dsub">Semangat hari ini! Yuk, pantau produksi dan stok bahan baku tempe!</p>
      </div>
      <div class="avatar" onclick="openNotifModal()" style="cursor:pointer" title="Lonceng Notifikasi"><svg viewBox="22 6 156 156"><use href="#mk" width="200" height="220"/></svg></div>
    </header>

    <div class="warn-banner" id="warnBanner">
      <svg viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm1 14h-2v-2h2v2zm0-4h-2V10h2v4z"/></svg>
      <div>⚠️ Peringatan: Stok kedelai kritis / di bawah batas minimum (100 kg)! Harap segera catat penerimaan kedelai.</div>
    </div>

    <div class="stok" id="stokCard" title="Klik untuk ubah stok">
      <svg viewBox="0 0 100 90">
        <use href="#bean" x="2" y="34" width="56" height="40" transform="rotate(-25 30 54)"/>
        <use href="#bean" x="40" y="6" width="56" height="40" transform="rotate(35 68 26)"/>
        <use href="#bean" x="38" y="44" width="58" height="42" transform="rotate(-8 67 65)"/>
      </svg>
      <div class="sinfo">
        <h3>Stok Kedelai Saat Ini <svg class="pen" viewBox="0 0 24 24" fill="none" stroke="#2F4F1E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4L19 9l-4-4L4 16z"/></svg></h3>
        <div class="track"><div class="fill" id="bar"></div></div>
        <div class="srow"><span><b id="now"></b> <span class="max" id="max"></span></span><span class="pct" id="pct"></span></div>
        <div class="min-info">Batas Minimum Stok: <span id="minStockVal">100</span> kg</div>
      </div>
    </div>

    <div class="quick-btns">
      <button class="btn-quick bq-add" id="btnQuickAdd">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Pesanan
      </button>
      <button class="btn-quick bq-stok" id="btnQuickStok">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Catat Penerimaan Kedelai
      </button>
    </div>

    <div class="sec-title">Ringkasan Pesanan</div>
    <div class="status-summary">
      <div class="sum-card">
        <div id="cntDijadwalkan">0</div>
        <span>Dijadwalkan</span>
      </div>
      <div class="sum-card">
        <div id="cntDiproduksi">0</div>
        <span>Diproduksi</span>
      </div>
      <div class="sum-card">
        <div id="cntSelesai">0</div>
        <span>Selesai</span>
      </div>
    </div>

    <div class="info-widgets">
      <div class="wcard">
        <div class="wcard-info">
          <h4>Menunggu Persetujuan</h4>
          <p>Pesanan baru butuh konfirmasi stok</p>
        </div>
        <div class="wcard-badge wb-wait" id="cntMenunggu">0 Pesanan</div>
      </div>

      <div class="wcard">
        <div class="wcard-info">
          <h4>Jadwal Produksi Terdekat</h4>
          <p id="nextScheduleInfo">Belum ada jadwal</p>
        </div>
        <div class="wcard-badge wb-sched" id="nextScheduleBadge">-</div>
      </div>
    </div>

    <div class="banner">
      <svg class="spark" width="40" height="40" viewBox="0 0 40 40" stroke="#BFD08E" stroke-width="3.5" stroke-linecap="round"><path d="M22 6L26 18M32 12L28 20M10 14L16 22"/></svg>
      <svg class="mk" viewBox="0 0 200 220"><use href="#mk" width="200" height="220"/></svg>
      <div>
        <h4>Si Tempi</h4>
        <p>siap membantu kelancaran produksi tempe!</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== Halaman 4: Input Pesanan ===== -->
<section class="page ord" id="order">
  <div class="hill"></div>
  <svg class="pl pl-l" viewBox="0 0 160 200"><use href="#plant" width="160" height="200"/></svg>
  <svg class="pl pl-r" viewBox="0 0 160 200"><use href="#plant" width="160" height="200"/></svg>

  <div class="owrap">
    <header class="ohead">
      <button class="back" id="back" aria-label="Kembali"><svg viewBox="0 0 24 24" fill="none" stroke="#1F2A18" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12H4M10 5l-7 7 7 7"/></svg></button>
      <h2 id="orderFormTitle">Input Pesanan</h2>
      <svg class="lf" viewBox="0 0 40 40"><use href="#leaf" width="40" height="40" style="color:#8DB35A" transform="rotate(15 20 20)"/></svg>
    </header>

    <input type="hidden" id="editOrderId" value="">

    <label class="lbl" for="nama">Nama Pelanggan</label>
    <div class="ofield">
      <svg viewBox="0 0 32 32" fill="none" stroke="#2F4F1E" stroke-width="2.4" stroke-linecap="round"><circle cx="16" cy="11" r="5.5"/><path d="M5 28c0-6.5 5-9.5 11-9.5s11 3 11 9.5"/></svg>
      <input type="text" id="nama" placeholder="Masukkan nama pelanggan" autocomplete="off">
    </div>

    <label class="lbl" for="jml">Jumlah Produk (Papan)</label>
    <div class="ofield">
      <svg viewBox="0 0 32 32" fill="none" stroke="#2F4F1E" stroke-width="2.4" stroke-linecap="round"><circle cx="16" cy="16" r="12.5"/><circle cx="11.5" cy="13.5" r="1.2" fill="#2F4F1E"/><circle cx="20.5" cy="13.5" r="1.2" fill="#2F4F1E"/><path d="M10.5 19.5Q16 25 21.5 19.5"/></svg>
      <input type="number" id="jml" min="1" step="any" placeholder="Masukkan jumlah (papan)" oninput="updateCalculations()">
      <span class="suf">papan</span>
    </div>

    <label class="lbl" for="tgl">Tanggal Permintaan</label>
    <div class="ofield" id="tglBox">
      <svg viewBox="0 0 32 32" fill="none" stroke="#2F4F1E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="7" width="22" height="20" rx="4"/><path d="M5 13.5h22M11 4v5M21 4v5"/><circle cx="16" cy="21" r="3"/></svg>
      <input type="text" id="tgl" style="display:none" readonly>
      <svg class="sm" viewBox="0 0 32 32" fill="none" stroke="#2F4F1E" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="7" width="22" height="20" rx="4"/><path d="M5 14h22M11 4v5M21 4v5"/><path d="M11 20h2M19 20h2"/></svg>
      <input type="date" id="tglH" aria-label="Tanggal permintaan" oninput="updateCalculations()" onchange="updateCalculations()">
    </div>

    <label class="lbl" for="alamat">Alamat</label>
    <div class="ofield">
      <svg viewBox="0 0 32 32" fill="none" stroke="#2F4F1E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M16 29C9.5 21 7 17 7 12.5a9 9 0 0 1 18 0C25 17 22.5 21 16 29z"/><circle cx="16" cy="12.5" r="3.5"/></svg>
      <input type="text" id="alamat" placeholder="Masukkan alamat pelanggan" autocomplete="off">
    </div>

    <div style="margin-top:16px; padding:16px; background:#EAEFD2; border-radius:18px; font-weight:700; font-size:15px; line-height:1.6;">
      <div>Kebutuhan Kedelai: <b id="calcKedelai">0 kg</b></div>
      <div>Estimasi Tgl Mulai Produksi: <b id="calcTglMulai">-</b></div>
      <div id="dateWarning" style="color:#C04B22; display:none; margin-top:4px;">⚠️ Tanggal mulai produksi sudah lewat (permintaan kurang dari 3 hari dari hari ini; proses tempe ±4 hari). Pesanan tetap bisa disimpan.</div>
    </div>

    <button class="simpan" id="simpan">
      <svg viewBox="0 0 32 32"><path d="M5 4h19l5 5v19a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" fill="#fff"/><rect x="9" y="4" width="12" height="8" fill="#4A7A2B"/><rect x="17" y="6" width="3" height="4" fill="#fff"/><circle cx="16" cy="21" r="4" fill="#4A7A2B"/></svg>
      Simpan Pesanan
    </button>

    <svg class="oscene" viewBox="0 0 640 250" xmlns="http://www.w3.org/2000/svg">
      <use href="#leaf" x="0" y="90" width="80" height="80" style="color:#8DB35A" transform="rotate(-20 40 130)"/>
      <use href="#leaf" x="40" y="150" width="70" height="70" style="color:#A9C873" transform="rotate(30 75 185)"/>
      <use href="#leaf" x="570" y="70" width="70" height="70" style="color:#8DB35A" transform="rotate(-10 605 105)"/>
      <use href="#leaf" x="530" y="150" width="80" height="80" style="color:#6E9A45" transform="rotate(25 570 190)"/>
      <g stroke="#9DBF66" stroke-width="5" stroke-linecap="round"><path d="M268 38L276 54"/><path d="M296 52L312 46"/></g>
      <use href="#mk" x="105" y="28" width="190" height="209"/>
      <g class="bub" id="bub">
        <path d="M343 112L298 134L356 138Z" fill="#FFFEF2" stroke="#E3D87E" stroke-width="3" stroke-linejoin="round"/>
        <ellipse cx="450" cy="100" rx="118" ry="68" fill="#FFFEF2" stroke="#E3D87E" stroke-width="3"/>
        <path d="M341 110L356 132L340 126Z" fill="#FFFEF2"/>
        <g font-family="Quicksand, sans-serif" font-weight="700" font-size="24" fill="#3F5A2E" text-anchor="middle">
          <text x="450" y="84">Pesanan</text><text x="450" y="112">sudah dicatat</text><text x="450" y="140">ya!</text>
        </g>
      </g>
    </svg>
  </div>
</section>

<!-- ===== Halaman 5: Daftar Pesanan ===== -->
<section class="page lst" id="list">
  <div class="lcol">
    <div class="lhead">
      <div class="lhead-title">
        <h2>Daftar Pesanan</h2>
        <svg viewBox="0 0 40 40"><use href="#leaf" width="40" height="40" style="color:#8DB35A" transform="rotate(15 20 20)"/></svg>
      </div>
      <button class="btn-add-order" id="btnAddOrder">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Pesanan
      </button>
    </div>

    <div class="search-box">
      <div class="search-field">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5A6B55" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" id="searchName" placeholder="Cari nama pelanggan...">
      </div>
    </div>

    <div class="pills" id="pills">
      <button class="pill on" data-f="Semua">Semua</button>
      <button class="pill" data-f="Menunggu">Menunggu</button>
      <button class="pill" data-f="Dijadwalkan">Dijadwalkan</button>
      <button class="pill" data-f="Diproduksi">Diproduksi</button>
      <button class="pill" data-f="Selesai">Selesai</button>
    </div>

    <div id="olist"></div>
  </div>
</section>

<!-- ===== HALAMAN LAPORAN MINGGUAN (TEMA UI CARD REVISI LENGKAP) ===== -->
<section class="page dash" id="laporanPage">
  <div class="lap-container">
    <div class="lap-header">
      <button onclick="show(dash)">←</button>
      <h2>Laporan</h2>
    </div>

    <!-- Tab Navigasi 3 Bagian (Produksi, Pesanan, Stok) -->
    <div class="lap-tabs">
      <button class="lap-tab active" onclick="switchLapTab('produksi', this)">Produksi</button>
      <button class="lap-tab" onclick="switchLapTab('pesanan', this)">Pesanan</button>
      <button class="lap-tab" onclick="switchLapTab('stok', this)">Stok</button>
    </div>

    <!-- Pemilih Periode Minggu -->
    <div class="lap-periode">
      <label>Periode</label>
      <div class="lap-periode-box">
        <span>📅</span>
        <select id="lapPeriodeSelect" onchange="renderLaporanData()"></select>
      </div>
    </div>

    <!-- Bagian 1: Produksi (Default View Sesuai Gambar) -->
    <div id="secProduksi" class="lap-view-section active">
      <div class="lap-card-item">
        <div class="lap-card-icon">🫘</div>
        <div>
          <div class="lap-card-lbl">Total Produksi</div>
          <div class="lap-card-val" id="lapValProduksi">1.250 kg</div>
        </div>
      </div>
      <div class="lap-card-item">
        <div class="lap-card-icon">📋</div>
        <div>
          <div class="lap-card-lbl">Total Pesanan</div>
          <div class="lap-card-val" id="lapValPesananDiproses">1.180 kg</div>
        </div>
      </div>
      <div class="lap-progress-card">
        <div class="lap-progress-head">
          <div class="lap-card-icon" style="background:#EAEFD2; width:48px; height:48px; font-size:20px;">✅</div>
          <div>
            <div class="lap-card-lbl">Persentase Capaian Pesanan</div>
            <div class="lap-card-val" style="font-size:22px" id="lapValPct">94%</div>
          </div>
        </div>
        <div class="lap-progress-bar">
          <div class="lap-progress-fill" id="lapFillPct" style="width: 94%;"></div>
        </div>
      </div>
    </div>

    <!-- Bagian 2: Riwayat Pesanan -->
    <div id="secPesanan" class="lap-view-section">
      <div class="lap-card-item">
        <div class="lap-card-icon">📦</div>
        <div>
          <div class="lap-card-lbl">Jumlah Total Pesanan</div>
          <div class="lap-card-val" id="lapValJumlahPesanan">280 Papan</div>
        </div>
      </div>
      <div style="background:#fff; border-radius:20px; padding:16px; margin-bottom:16px; box-shadow:0 3px 10px rgba(0,0,0,.05);">
        <h4 style="font-size:15px; margin-bottom:10px;">Ringkasan Status Pesanan</h4>
        <div class="drow"><span>Selesai:</span><b id="lapPsnSelesai">190 Papan</b></div>
        <div class="drow"><span>Diproduksi / Dijadwalkan:</span><b id="lapPsnJadwal">0 Papan</b></div>
        <div class="drow"><span>Menunggu:</span><b id="lapPsnMenunggu">0 Papan</b></div>
      </div>
    </div>

    <!-- Bagian 3: Penerimaan & Penggunaan Kedelai (Stok) -->
    <div id="secStok" class="lap-view-section">
      <div class="lap-card-item">
        <div class="lap-card-icon">📥</div>
        <div>
          <div class="lap-card-lbl">Kedelai Masuk (Penerimaan)</div>
          <div class="lap-card-val" id="lapValKedelaiMasuk">600 kg</div>
        </div>
      </div>
      <div class="lap-card-item">
        <div class="lap-card-icon">📤</div>
        <div>
          <div class="lap-card-lbl">Kedelai Terpakai (Penggunaan)</div>
          <div class="lap-card-val" id="lapValKedelaiKeluar">162 kg</div>
        </div>
      </div>
      <div class="lap-card-item" style="background:#EAEFD2;">
        <div class="lap-card-icon" style="background:#fff;">⚖️</div>
        <div>
          <div class="lap-card-lbl">Stok Akhir Kedelai</div>
          <div class="lap-card-val" id="lapValStokAkhir">680 kg</div>
        </div>
      </div>
    </div>

    <!-- Tombol Cetak / Detail Laporan -->
    <button class="btn-lap-main" onclick="cetakLaporan()">Lihat Detail Laporan ➔</button>
  </div>
</section>

<!-- Bottom Navigasi -->
<div class="bnav">
  <div class="in">
    <button class="nv on" data-target="dash">
      <svg viewBox="0 0 32 32"><path d="M16 3L2 15.500h4V28h7.500v-8h5v8H26V15.500h4z"/></svg>
      Beranda
    </button>
    <button class="nv" data-target="list">
      <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.500" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="20" height="24" rx="4"/><path d="M11 11h10M11 16h10M11 21h6"/></svg>
      Pesanan
    </button>
    <button class="nv" data-target="laporanPage">
      <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="3"><circle cx="16" cy="16" r="9"/><circle cx="16" cy="16" r="12.500" stroke-width="4" stroke-dasharray="4.900 4.900"/><circle cx="16" cy="16" r="3.500"/></svg>
      Laporan
    </button>
    <button class="nv" data-target="logout" aria-label="Keluar">
      <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5H7a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h5M21 10l6 6-6 6M27 16H12"/></svg>
      Keluar
    </button>
  </div>
</div>

<div class="toast" id="toast"></div>

<!-- Modal detail pesanan -->
<div class="modal" id="odModal">
  <div class="mbox">
    <h3 id="odT"></h3>
    <div id="odB"></div>
    <div class="mrow" id="odA"></div>
  </div>
</div>

<!-- MODAL PEMERIKSAAN STOK PESANAN -->
<div class="modal" id="pemeriksaanModal">
  <div class="mbox" style="position:relative">
    <button type="button" aria-label="Tutup" title="Tutup (tidak jadi)" onclick="closeModals()" style="position:absolute;top:14px;right:16px;width:36px;height:36px;border:0;border-radius:50%;background:#F1F4E4;color:#2F4F1E;font:700 20px 'Quicksand',sans-serif;cursor:pointer">✕</button>
    <h3>Pemeriksaan & Persetujuan Stok Pesanan</h3>
    <p style="margin-top:6px; color:#5A6B55;">Memastikan kedelai cukup pada tanggal produksi sebelum disetujui.</p>
    <div id="pemeriksaanBody" style="margin-top:16px; font-weight:600; font-size:15px; line-height:1.6;"></div>
    <div class="mrow">
      <button class="m-no" id="pemTolak" style="background:#F8E3D9; color:#9A4B2B">Tolak Pesanan</button>
      <button class="m-ok" id="pemOk">Setujui & Jadwalkan</button>
    </div>
  </div>
</div>

<!-- MODAL PENERIMAAN / PEMBELIAN KEDELAI (ANGKA 6 DIHAPUS) -->
<div class="modal" id="modal">
  <div class="mbox">
    <h3 id="mTitle">Penerimaan / Pembelian Kedelai</h3>
    <p>Mencatat kedelai masuk agar stok bertambah otomatis.</p>
    
    <label>Tanggal Penerimaan<input type="date" id="penerimaanTgl"></label>
    <label>Sumber Kedelai
      <select id="penerimaanSumber">
        <option value="Pemasok Utama">Pemasok Utama</option>
        <option value="Toko Terdekat">Toko Terdekat</option>
      </select>
    </label>
    <label>Jumlah Kedelai (kg)<input type="number" id="mNow" min="0" step="any" placeholder="Masukkan jumlah kg"></label>
    <label>Harga Pembelian per kg (Rp)<input type="number" id="penerimaanHarga" min="0" step="any" placeholder="Contoh: 11000"></label>
    
    <div style="margin-top:20px;">
      <h4 style="font-size:15px; font-weight:700; color:#2F4F1E; margin-bottom:8px;">Tabel Riwayat Penerimaan Kedelai</h4>
      <table class="tbl-riwayat">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Sumber</th>
            <th>Jumlah</th>
            <th>Harga/kg</th>
          </tr>
        </thead>
        <tbody id="tbRiwayatBody">
        </tbody>
      </table>
    </div>

    <div class="mrow">
      <button class="m-no" id="mNo">Batal</button>
      <button class="m-ok" id="mOk">Simpan</button>
    </div>
  </div>
</div>

<!-- MODAL PENGGUNAAN KEDELAI -->
<div class="modal" id="modalPenggunaan">
  <div class="mbox">
    <h3>Riwayat Penggunaan Kedelai</h3>
    <table class="tbl-riwayat">
      <thead><tr><th>Tanggal</th><th>Jadwal</th><th>Penggunaan (kg)</th></tr></thead>
      <tbody id="tbPenggunaanBody"></tbody>
    </table>
    <div class="mrow"><button class="m-no" onclick="closeModals()">Tutup</button></div>
  </div>
</div>

<div class="modal" id="modalJadwal"><div class="mbox"><h3>Jadwal Produksi</h3><div id="jadwalInfo" style="margin:4px 0 10px;font-size:13px"></div>
  <table class="tbl-riwayat"><thead><tr><th>Mulai</th><th>Selesai</th><th>Pesanan</th><th>Kedelai (rutin+pesanan)</th></tr></thead><tbody id="tbJadwalBody"></tbody></table>
  <div class="mrow" style="flex-wrap:wrap"><button class="m-no" onclick="closeModals()">Tutup</button><button class="m-no" onclick="closeModals();openHarianModal()">Produksi Harian</button><button class="m-ok" onclick="produksiRutin()">Koreksi Produksi Hari Ini</button></div></div></div>
<div class="modal" id="modalHarian"><div class="mbox" style="position:relative">
  <button type="button" aria-label="Tutup" onclick="closeModals()" style="position:absolute;top:14px;right:16px;width:36px;height:36px;border:0;border-radius:50%;background:#F1F4E4;color:#2F4F1E;font:700 20px 'Quicksand',sans-serif;cursor:pointer">✕</button>
  <h3>Produksi Harian</h3><div id="harianInfo" style="margin:4px 0 10px;font-size:13px"></div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end;margin-bottom:10px">
    <label style="flex:1;min-width:130px;font-size:13px">Tanggal<input type="date" id="hrTgl" style="width:100%;box-sizing:border-box;padding:10px;border:1px solid #CFD6B8;border-radius:10px;font:inherit"></label>
    <label style="flex:1;min-width:110px;font-size:13px">Kedelai (kg)<input type="number" step="any" min="0" id="hrKg" style="width:100%;box-sizing:border-box;padding:10px;border:1px solid #CFD6B8;border-radius:10px;font:inherit"></label>
    <button class="btn-act btn-flow" style="height:44px;padding:0 18px" onclick="simpanHarian()">Simpan</button></div>
  <div style="overflow-x:auto"><table class="tbl-riwayat"><thead><tr><th>Tanggal</th><th>Rutin (kg)</th><th>Pesanan (kg)</th><th>Total (kg)</th><th></th></tr></thead><tbody id="tbHarianBody"></tbody></table></div>
  <div class="mrow" style="flex-wrap:wrap"><button class="m-no" onclick="closeModals()">Tutup</button><button class="m-no" onclick="ubahStandar()">Ubah Standar</button><button class="m-ok" onclick="produksiRutin()">Koreksi Pemakaian Hari Ini</button></div>
</div></div>
<!-- MODAL NOTIFIKASI STOK -->
<div class="modal" id="modalNotif">
  <div class="mbox">
    <h3>🔔 Notifikasi Stok</h3>
    <div id="notifListContent" style="margin-top:14px;"></div>
    <div class="mrow">
      <button class="m-no" onclick="closeModals()">Tutup</button>
      <button class="m-ok" onclick="closeModals(); openPenerimaanModal()">Catat Penerimaan Kedelai</button>
    </div>
  </div>
</div>

<div class="modal" id="dlg"><div class="mbox"><h3 id="dlgT"></h3><p id="dlgM"></p><div id="dlgF"></div>
  <div class="mrow"><button class="m-no" id="dlgNo">Batal</button><button class="m-ok" id="dlgOk">Simpan</button></div></div></div>
<!-- MODAL DAFTAR AKUN BARU -->
<div class="modal" id="regModal">
  <div class="mbox">
    <h3>Daftar Akun Baru</h3>
    <p>Buat akun baru untuk mengelola produksi tempe.</p>
    <label>Nama Lengkap<input type="text" id="regName" placeholder="Masukkan nama Anda"></label>
    <label>Email / Username<input type="text" id="regUser" placeholder="Masukkan username"></label>
    <label>Password<input type="password" id="regPw" placeholder="Masukkan password"></label>
    <div class="mrow">
      <button class="m-no" id="regCancel">Batal</button>
      <button class="m-ok" id="regSubmit">Daftar</button>
    </div>
  </div>
</div>

@endverbatim
<script>window.BOOT = @json($boot); window.CSRF = @json(csrf_token());</script>
@verbatim
<script>
  // Halaman & Navigasi Utama
  const splash = document.getElementById('splash');
  const login = document.getElementById('login');
  const dash = document.getElementById('dash');
  const order = document.getElementById('order');
  const list = document.getElementById('list');
  const laporanPage = document.getElementById('laporanPage');
  const sidebar = document.getElementById('sidebar');

  document.getElementById('mulai').onclick = () => {
    splash.classList.remove('active'); login.classList.add('active'); window.scrollTo(0, 0);
  };

  document.getElementById('sbToggle').onclick = () => sidebar.classList.add('show');
  document.getElementById('sbClose').onclick = () => sidebar.classList.remove('show');

  // Tampil / Sembunyikan Password
  const pw = document.getElementById('pw');
  document.getElementById('eye').onclick = () => { pw.type = pw.type === 'password' ? 'text' : 'password'; };

  // FITUR DAFTAR AKUN BARU
  const regModal = document.getElementById('regModal');
  const btnShowRegister = document.getElementById('btnShowRegister');
  const regCancel = document.getElementById('regCancel');
  const regSubmit = document.getElementById('regSubmit');

  btnShowRegister.onclick = () => {
    document.getElementById('regName').value = '';
    document.getElementById('regUser').value = '';
    document.getElementById('regPw').value = '';
    regModal.classList.add('show');
  };
  regCancel.onclick = () => regModal.classList.remove('show');

  const api = (u, b) => fetch(u, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.CSRF, 'Accept': 'application/json' }, body: JSON.stringify(b || {}) }).then(async r => ({ ok: r.ok, d: await r.json().catch(() => ({})) }));
  const enter = n => { state.session = n; document.getElementById('displayUserName').textContent = n; show(dash); bar.style.width = '0%'; setTimeout(renderStok, 150); };
  regSubmit.onclick = async () => {
    const nama = document.getElementById('regName').value.trim(), user = document.getElementById('regUser').value.trim(), pw = document.getElementById('regPw').value.trim();
    if (!nama || !user || !pw) { showToast('Lengkapi semua data pendaftaran ya.', true); return; }
    const r = await api('/auth/register', { nama, user, pw });
    if (!r.ok) { showToast((r.d.errors && Object.values(r.d.errors)[0][0]) || r.d.message || 'Pendaftaran gagal.', true); return; }
    location.reload();
  };
  document.getElementById('masuk').onclick = async () => {
    const msg = document.getElementById('msg'); msg.style.color = '#9A4B2B';
    const r = await api('/auth/login', { user: document.getElementById('user').value.trim(), pw: document.getElementById('pw').value.trim() });
    if (!r.ok) { msg.textContent = 'Username atau password salah'; return; }
    location.reload();
  };

  // Konfigurasi
  const SATUAN = 'papan', KEDELAI_PER_SATUAN = 60 / 91, HARIAN = 65, LEAD = 3;
  const iso = d => iso2(d), iso2 = d => new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
  const todayIso = () => iso(new Date());
  const addDays = (t, n) => { const d = new Date(t + 'T00:00:00'); d.setDate(d.getDate() + n); return iso(d); };
  const T = n => addDays(todayIso(), n);
  const butuh = o => o.jml * KEDELAI_PER_SATUAN;
  const harianOn = t => (state.rencana && state.rencana[t] != null) ? state.rencana[t] : state.harian;
  const mulaiOf = o => addDays(o.tanggal, -LEAD);

  const SEED = [
    { id: 'P001', nama: 'Ibu Sari', jml: 50, tanggal: T(9), status: 'Menunggu', alamat: 'Jl. Soekarno Hatta No. 12' },
    { id: 'P002', nama: 'Earlyta', jml: 100, tanggal: T(5), mulai: T(2), status: 'Dijadwalkan', alamat: 'Malang' },
    { id: 'P003', nama: 'Ita', jml: 30, tanggal: T(2), mulai: T(-1), status: 'Diproduksi', alamat: 'Malang' },
    { id: 'P004', nama: 'Safina', jml: 30, tanggal: T(-2), mulai: T(-5), status: 'Selesai', alamat: 'Malang' },
    { id: 'P005', nama: 'Restoran Fina', jml: 60, tanggal: T(-9), mulai: T(-12), status: 'Selesai', alamat: 'Malang' }
  ];
  const SEED_RIWAYAT = [{ tgl: T(-5), sumber: 'Pemasok Utama', jml: 500, harga: 11000 }, { tgl: T(-10), sumber: 'Toko Terdekat', jml: 100, harga: 11500 }];
  const SEED_PAKAI = [1, 2, 3, 4, 5, 6].map(n => ({ tgl: T(-n), jadwal: 'Produksi rutin harian', jml: HARIAN, rutin: true })).concat([{ tgl: T(-1), jadwal: '#P003 - Ita', jml: 18 }, { tgl: T(-5), jadwal: '#P004 - Safina', jml: 18 }, { tgl: T(-12), jadwal: '#P005 - Restoran Fina', jml: 36 }]);

  let state = Object.assign({ now: 0, min: 100, max: 1000, orders: [], riwayat: [], penggunaan: [], users: [], harian: HARIAN, rencana: {}, session: null, harianStart: todayIso() }, window.BOOT.state || {});
  if (!state.harianStart) state.harianStart = todayIso();
  let _st; const save = () => { if (!window.BOOT.user) return; clearTimeout(_st); _st = setTimeout(() => fetch('/sync', { method: 'POST', keepalive: true, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.CSRF, 'Accept': 'application/json' }, body: JSON.stringify(state) }).catch(() => {}), 250); };
  const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const fmt = n => n.toLocaleString('id-ID', { maximumFractionDigits: 1 });

  // Stok aktual, teralokasi, bebas (sesuai flowchart stok)
  const alokasi = () => state.orders.filter(o => o.status === 'Dijadwalkan').reduce((a, o) => a + butuh(o), 0);
  const bebas = () => state.now - alokasi();
const alokasiSebelum = (M, ex) => {
    const h0 = todayIso(), sudah = state.penggunaan.some(x => x.rutin && x.tgl === h0), dari = sudah ? addDays(h0, 1) : h0;
    let rutin = 0; for (let d = dari; d < M; d = addDays(d, 1)) if (!state.penggunaan.some(x => x.rutin && x.tgl === d)) rutin += harianOn(d);
    const psn = state.orders.filter(o => o.id !== ex && o.status === 'Dijadwalkan' && (o.mulai || mulaiOf(o)) < M).reduce((a, o) => a + butuh(o), 0);
    return { rutin, psn, total: rutin + psn, dari };
  };
  const cekPesanan = o => {
    const M = o.mulai || mulaiOf(o), A = alokasiSebelum(M, o.id), b = butuh(o);
    const ps = state.orders.filter(x => x.id !== o.id && x.status === 'Dijadwalkan' && (x.mulai || mulaiOf(x)) === M).reduce((a, x) => a + butuh(x), 0);
    const rutinM = state.penggunaan.some(x => x.rutin && x.tgl === M) ? 0 : harianOn(M);
    const totalProd = rutinM + b + ps, bb = state.now - A.total;
    return { M, A, b, ps, rutinM, totalProd, bb, sisa: bb - totalProd };
  };
  const peringatan = () => {
    const w = [];
    if (state.now <= state.min) w.push(`Stok mencapai batas minimum (${fmt(state.now)} kg, minimum ${fmt(state.min)} kg).`);
    if (alokasi() > state.now) w.push(`Stok tidak cukup untuk pesanan terjadwal, kurang ${fmt(alokasi() - state.now)} kg.`);
    state.orders.filter(o => o.status === 'Menunggu').forEach(o => { const c = cekPesanan(o); if (c.sisa < 0) w.push(`Pesanan #${o.id} (${esc(o.nama)}) butuh ${fmt(c.totalProd)} kg pada ${c.M}, stok tersedia saat itu ${fmt(c.bb)} kg.`); });
    return w;
  };

  const bar = document.getElementById('bar');
  const warnBanner = document.getElementById('warnBanner');

  function renderStok() {
    const pct = Math.max(0, Math.min(100, Math.round(state.now / state.max * 100)));
    bar.style.width = pct + '%';
    document.getElementById('now').textContent = fmt(state.now) + ' kg';
    document.getElementById('max').textContent = '/ ' + fmt(state.max) + ' kg';
    document.getElementById('pct').textContent = pct + '%';
    document.getElementById('minStockVal').textContent = fmt(state.min);
    const w = peringatan();
    warnBanner.querySelector('div').innerHTML = '⚠️ Peringatan: ' + w[0] + (w.length > 1 ? ` (+${w.length - 1} peringatan lain)` : '') + ' Segera catat penerimaan kedelai.';
    warnBanner.classList.toggle('show', w.length > 0);
    document.getElementById('notifBadgeCnt').textContent = w.length;
    renderDashboardSummary();
  }

  function renderRiwayatPenerimaan() {
    const tbody = document.getElementById('tbRiwayatBody');
    tbody.innerHTML = state.riwayat.length ? state.riwayat.map(r => `<tr><td>${esc(r.tgl)}</td><td>${esc(r.sumber)}</td><td>${fmt(r.jml)} kg</td><td>Rp ${fmt(r.harga)}</td></tr>`).join('') : '<tr><td colspan="4" style="text-align:center;">Belum ada riwayat.</td></tr>';
  }

  function renderDashboardSummary() {
    const count = { Menunggu: 0, Dijadwalkan: 0, Diproduksi: 0, Selesai: 0 };
    let next = null;
    state.orders.forEach(o => {
      if (count[o.status] !== undefined) count[o.status]++;
      if (o.status === 'Dijadwalkan' && (!next || o.mulai < next.mulai)) next = o;
    });
    document.getElementById('cntDijadwalkan').textContent = count.Dijadwalkan;
    document.getElementById('cntDiproduksi').textContent = count.Diproduksi;
    document.getElementById('cntSelesai').textContent = count.Selesai;
    document.getElementById('cntMenunggu').textContent = count.Menunggu + ' Pesanan';
    document.getElementById('nextScheduleInfo').textContent = next ? `Mulai produksi: ${next.nama} (${next.jml} papan)` : 'Belum ada jadwal terdekat';
    document.getElementById('nextScheduleBadge').textContent = next ? next.mulai : '-';
  }

  renderStok();

  const toast = document.getElementById('toast');
  let toastTimer;
  function showToast(msg, err) {
    toast.textContent = msg;
    toast.classList.toggle('err', !!err);
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), err ? 3400 : 2600);
  }

  function resetOrderForm() {
    document.getElementById('editOrderId').value = '';
    document.getElementById('orderFormTitle').textContent = 'Input Pesanan';
    ['nama', 'jml', 'alamat'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('tglH').value = '';
    document.getElementById('tgl').value = '';
    document.getElementById('calcKedelai').textContent = '0 kg';
    document.getElementById('calcTglMulai').textContent = '-';
    document.getElementById('dateWarning').style.display = 'none';
  }

  function updateCalculations() {
    const jml = parseFloat(document.getElementById('jml').value) || 0;
    const tglH = document.getElementById('tglH').value;
    const butuh = jml * KEDELAI_PER_SATUAN;
    document.getElementById('calcKedelai').textContent = fmt(butuh) + ' kg';

    if (tglH) {
      const d = new Date(tglH + 'T00:00:00');
      d.setDate(d.getDate() - 3);
      document.getElementById('calcTglMulai').textContent = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

      const today = new Date();
      const diffTime = new Date(tglH + 'T00:00:00') - today;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      document.getElementById('dateWarning').style.display = addDays(tglH, -LEAD) < todayIso() ? 'block' : 'none';
    }
  }

  function show(p) {
    document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
    p.classList.add('active');
    sidebar.classList.remove('show');
    window.scrollTo(0, 0);

    if (p === dash || p === list || p === laporanPage) {
      document.body.classList.add('has-nav');
      document.querySelectorAll('.nv').forEach(btn => {
        btn.classList.toggle('on', btn.dataset.target === p.id);
      });
    } else {
      document.body.classList.remove('has-nav');
    }

    if (p === dash) renderStok();
    if (p === list) renderOrders();
    if (p === laporanPage) renderLaporanData();
  }

  function closeModals() { document.querySelectorAll('.modal').forEach(m => m.classList.remove('show')); }

  document.getElementById('btnQuickAdd').onclick = () => { resetOrderForm(); show(order); };
  
  // OPEN MODAL PENERIMAAN KEDELAI
  window.openPenerimaanModal = function() {
    document.getElementById('mTitle').textContent = 'Penerimaan / Pembelian Kedelai';
    document.getElementById('penerimaanTgl').valueAsDate = new Date();
    document.getElementById('mNow').value = '';
    document.getElementById('penerimaanHarga').value = '';
    renderRiwayatPenerimaan();
    modal.classList.add('show');
  };
  document.getElementById('btnQuickStok').onclick = openPenerimaanModal;
  document.getElementById('stokCard').onclick = openPenerimaanModal;

  document.getElementById('back').onclick = () => {
    show(dash); bar.style.width = '0%'; setTimeout(renderStok, 100);
  };

  const modal = document.getElementById('modal');
  const mNow = document.getElementById('mNow');
  document.getElementById('mNo').onclick = () => modal.classList.remove('show');
  
  // SIMPAN PENERIMAAN KEDELAI
  document.getElementById('mOk').onclick = () => {
    const tglPenerimaan = document.getElementById('penerimaanTgl').value;
    const sumberPenerimaan = document.getElementById('penerimaanSumber').value;
    const n = parseFloat(mNow.value);
    const hargaBeli = parseFloat(document.getElementById('penerimaanHarga').value) || 0;

    if (!tglPenerimaan || isNaN(n) || n <= 0) { 
      showToast('Isi tanggal dan jumlah kedelai dengan benar.', true); 
      return; 
    }

    state.now = Math.round((state.now + n) * 100) / 100;
    if (!state.riwayat) state.riwayat = [];
    state.riwayat.unshift({ tgl: tglPenerimaan, sumber: sumberPenerimaan, jml: n, harga: hargaBeli });

    save(); 
    renderStok();
    modal.classList.remove('show');
    showToast(`Stok kedelai bertambah +${fmt(n)} kg otomatis!`);
  };

  const tgl = document.getElementById('tgl');
  const tglH = document.getElementById('tglH');
  
  tglH.onchange = () => {
    if (tglH.value) {
      tgl.value = new Date(tglH.value + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    } else {
      tgl.value = '';
    }
    updateCalculations();
  };

  document.getElementById('simpan').onclick = async () => {
    const nama = document.getElementById('nama').value.trim();
    const jml = parseFloat(document.getElementById('jml').value);
    const alamat = document.getElementById('alamat').value.trim();
    const editId = document.getElementById('editOrderId').value;
    if (!nama || isNaN(jml) || jml <= 0 || !tglH.value || !alamat) { showToast('Lengkapi semua data pesanan dulu ya.', true); return; }
    const mulai = addDays(tglH.value, -LEAD);
    if (mulai < todayIso() && await dlg('Tanggal sudah lewat / terlalu dekat', `Mulai produksi seharusnya ${mulai} (${LEAD} hari sebelum tanggal permintaan) dan sudah lewat dari hari ini. Pesanan tetap bisa disimpan, tapi tempe mungkin tidak siap tepat waktu.`, [], 'Tetap simpan') === null) return;
    if (editId) {
      const o = state.orders.find(x => x.id === editId);
      if (!o || !['Menunggu', 'Dijadwalkan'].includes(o.status)) { showToast('Pesanan ini sudah diproduksi, tidak bisa diubah.', true); return; }
      if (o.status === 'Dijadwalkan' && alokasi() - butuh(o) + jml * KEDELAI_PER_SATUAN > state.now) { showToast('Stok tidak cukup untuk jumlah baru.', true); return; }
      Object.assign(o, { nama, jml, tanggal: tglH.value, alamat, mulai });
      showToast('Pesanan berhasil diperbarui!');
    } else {
      const maxN = Math.max(0, ...state.orders.map(x => parseInt(x.id.slice(1)) || 0));
      state.orders.unshift({ id: 'P' + String(maxN + 1).padStart(3, '0'), nama, jml, tanggal: tglH.value, mulai, alamat, status: 'Menunggu' });
      const bub = document.getElementById('bub'); bub.classList.remove('pop'); void bub.getBoundingClientRect(); bub.classList.add('pop');
      showToast('Pesanan tersimpan dengan status Menunggu!');
    }
    save(); resetOrderForm(); show(list);
  };

  let currentFilter = 'Semua';
  let searchQuery = '';
  const olist = document.getElementById('olist');
  const pills = document.getElementById('pills');
  const searchName = document.getElementById('searchName');
  const btnAddOrder = document.getElementById('btnAddOrder');

  btnAddOrder.onclick = () => {
    resetOrderForm();
    show(order);
  };

  searchName.oninput = (e) => {
    searchQuery = e.target.value.toLowerCase().trim();
    renderOrders();
  };

  pills.querySelectorAll('.pill').forEach(btn => {
    btn.onclick = () => {
      pills.querySelectorAll('.pill').forEach(p => p.classList.remove('on'));
      btn.classList.add('on');
      currentFilter = btn.dataset.f;
      renderOrders();
    };
  });

  function renderOrders() {
    const filtered = state.orders.filter(o => (currentFilter === 'Semua' || o.status === currentFilter) && o.nama.toLowerCase().includes(searchQuery));
    if (!filtered.length) { olist.innerHTML = '<div class="kosong">Tidak ada pesanan yang ditemukan.</div>'; return; }
    olist.innerHTML = '';
    const flow = { Menunggu: ['setujuiPesanan', 'Setujui & Jadwalkan'], Dijadwalkan: ['mulaiProduksi', 'Mulai Produksi'], Diproduksi: ['selesaikanProduksi', 'Tandai Selesai'] };
    filtered.forEach(o => {
      const div = document.createElement('div'); div.className = 'oi';
      const bc = { Dijadwalkan: 'b-d', Diproduksi: 'b-p', Selesai: 'b-s' }[o.status] || 'b-w';
      const ok = o.status !== 'Menunggu', editable = !['Diproduksi', 'Selesai'].includes(o.status);
      const f = flow[o.status];
      div.innerHTML = `
        <div class="oi-head"><div><div class="oi-name">${esc(o.nama)}</div><div class="oi-id">ID: #${o.id}</div></div>
          <div class="oi-status-row"><span class="badge ${ok ? 'b-app-true' : 'b-app-false'}">${ok ? 'Disetujui' : 'Belum Disetujui'}</span><span class="badge ${bc}">${esc(o.status)}</span></div></div>
        <div class="oi-body">
          <div><span>Jumlah (Papan)</span><b>${fmt(o.jml)} papan</b></div>
          <div><span>Kebutuhan Kedelai</span><b>± ${fmt(butuh(o))} kg</b></div>
          <div><span>Tanggal Permintaan</span><b>${esc(o.tanggal)}</b></div>
          <div><span>Mulai Produksi</span><b>${esc(o.mulai || mulaiOf(o))}</b></div>
          <div><span>Alamat</span><b>${esc(o.alamat || '-')}</b></div></div>
        <div class="oi-actions">
          ${editable ? `<button class="btn-act btn-edit" onclick="event.stopPropagation(); editPesanan('${o.id}')">Ubah</button><button class="btn-act btn-cancel" onclick="event.stopPropagation(); batalkanPesanan('${o.id}')">Batalkan</button>` : ''}
          ${f ? (o.status === 'Dijadwalkan' && todayIso() < o.mulai
            ? `<button class="btn-act btn-flow" disabled style="opacity:.5;cursor:not-allowed" title="Belum waktunya">Mulai: ${o.mulai}</button>`
            : `<button class="btn-act btn-flow" onclick="event.stopPropagation(); ${f[0]}('${o.id}')">${f[1]}</button>`) : ''}
        </div>`;
      div.onclick = () => openOrderModal(o);
      olist.appendChild(div);
    });
  }

  window.goList = f => { currentFilter = f; pills.querySelectorAll('.pill').forEach(p => p.classList.toggle('on', p.dataset.f === f)); show(list); };

  window.editPesanan = function(id) {
    const o = state.orders.find(x => x.id === id);
    if (!o || !['Menunggu', 'Dijadwalkan'].includes(o.status)) return;
    document.getElementById('editOrderId').value = o.id;
    document.getElementById('orderFormTitle').textContent = 'Ubah Pesanan #' + o.id;
    document.getElementById('nama').value = o.nama; document.getElementById('jml').value = o.jml;
    document.getElementById('alamat').value = o.alamat || ''; tglH.value = o.tanggal;
    tgl.value = new Date(o.tanggal + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    updateCalculations(); show(order);
  };

  window.batalkanPesanan = async function(id) {
    const o = state.orders.find(x => x.id === id);
    if (!o || !['Menunggu', 'Dijadwalkan'].includes(o.status)) { showToast('Pesanan yang sudah diproduksi tidak bisa dibatalkan.', true); return; }
    if (await dlg('Batalkan pesanan?', `Pesanan #${o.id} (${o.nama}) akan dihapus.`, [], 'Ya, batalkan') !== null) {
      state.orders = state.orders.filter(x => x.id !== id); save(); renderOrders(); renderStok(); showToast('Pesanan berhasil dibatalkan.');
    }
  };

  let targetOrderIdToApprove = null;
  const pemeriksaanModal = document.getElementById('pemeriksaanModal'), pemeriksaanBody = document.getElementById('pemeriksaanBody');
  const pemOk = document.getElementById('pemOk'), pemTolak = document.getElementById('pemTolak');

  window.setujuiPesanan = function(id) {
    const o = state.orders.find(x => x.id === id); if (!o) return;
    targetOrderIdToApprove = id;
    const c = cekPesanan(o), cukup = c.sisa >= 0, A = c.A;
    const rentang = c.M > A.dari ? `${A.dari} s/d ${addDays(c.M, -1)}` : 'tidak ada';
    const st = cukup
      ? `<div style="margin-top:10px;padding:10px 14px;border-radius:12px;background:#D5EAD8;color:#2E6B38;">✔ <b>Layak stok.</b> Stok tersisa setelah persetujuan: <b>${fmt(c.sisa)} kg</b></div>`
      : `<div style="margin-top:10px;padding:10px 14px;border-radius:12px;background:#F8E3D9;color:#9A4B2B;">⚠️ <b>Stok kurang ${fmt(-c.sisa)} kg.</b> Pilih tindakan:
          <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px">
          <button class="btn-act btn-edit" onclick="closeModals();openPenerimaanModal()">Pembelian tambahan</button>
          <button class="btn-act btn-edit" onclick="closeModals();editPesanan('${id}')">Jadwalkan ulang</button></div></div>`;
    pemOk.disabled = !cukup; pemOk.style.opacity = cukup ? '1' : '.5'; pemOk.style.cursor = cukup ? 'pointer' : 'not-allowed';
    const sub = 'style="font-size:13px;opacity:.85"';
    pemeriksaanBody.innerHTML = `
      <div class="drow"><span>Pelanggan</span><span>${esc(o.nama)}</span></div>
      <div class="drow"><span>Total Kebutuhan Pesanan</span><span>${fmt(c.b)} kg</span></div>
      <div class="drow"><span>Mulai Produksi</span><span>${c.M}</span></div>
      <div class="drow"><span>Total Kedelai Produksi tgl ${c.M} (rutin ${fmt(c.rutinM)} kg + pesanan)</span><span>${fmt(c.totalProd)} kg</span></div>
      <div class="drow"><span>Stok Aktual</span><span>${fmt(state.now)} kg</span></div>
      <div class="drow"><span>Sudah Dialokasikan sebelum ${c.M}</span><span>${fmt(A.total)} kg</span></div>
      <div class="drow" ${sub}><span>• Produksi harian (${rentang})</span><span>${fmt(A.rutin)} kg</span></div>
      <div class="drow" ${sub}><span>• Pesanan terjadwal sebelumnya</span><span>${fmt(A.psn)} kg</span></div>
      <div class="drow"><span>Stok Tersedia pada ${c.M}</span><span>${fmt(c.bb)} kg</span></div>${st}`;
    pemeriksaanModal.classList.add('show');
  };

  pemTolak.onclick = async () => {
    if (await dlg('Tolak pesanan?', 'Data pesanan akan dihapus.', [], 'Ya, tolak') !== null) {
      state.orders = state.orders.filter(x => x.id !== targetOrderIdToApprove); save(); renderOrders(); renderStok();
      pemeriksaanModal.classList.remove('show'); showToast('Pesanan telah ditolak dan dihapus.');
    }
  };

  pemOk.onclick = () => {
    const o = state.orders.find(x => x.id === targetOrderIdToApprove); if (!o) return;
    o.status = 'Dijadwalkan'; o.mulai = mulaiOf(o); save(); renderOrders(); renderStok();
    pemeriksaanModal.classList.remove('show'); showToast('Pesanan disetujui, dijadwalkan mulai ' + o.mulai);
  };

  const dlg = (t, m, fields, ok) => new Promise(res => {
    const el = document.getElementById('dlg');
    document.getElementById('dlgT').textContent = t; document.getElementById('dlgM').textContent = m || '';
    document.getElementById('dlgF').innerHTML = (fields || []).map((f, i) => `<label>${f.l}<input type="${f.t || 'number'}" step="any" min="0" id="dlgI${i}" value="${f.v}"></label>`).join('');
    document.getElementById('dlgOk').textContent = ok || 'Simpan';
    const done = v => { el.classList.remove('show'); res(v); };
    document.getElementById('dlgOk').onclick = () => done((fields || []).map((f, i) => { const v = document.getElementById('dlgI' + i).value; return f.t ? v : parseFloat(v); }));
    document.getElementById('dlgNo').onclick = () => done(null);
    el.classList.add('show');
  });
  const setRutin = (d, v) => {
    let e = state.penggunaan.find(x => x.rutin && x.tgl === d), diff = v;
    if (e) { diff = v - e.jml; e.jml = v; } else state.penggunaan.unshift({ tgl: d, jadwal: 'Produksi rutin harian', jml: v, rutin: true });
    const kurang = state.now - diff < 0; state.now = Math.max(0, Math.round((state.now - diff) * 100) / 100); return kurang;
  };
  const syncRutin = d => { if (d <= todayIso() && (d >= state.harianStart || state.penggunaan.some(x => x.rutin && x.tgl === d))) setRutin(d, harianOn(d)); };
  function postHarian() {
    let n = 0, kurang = false;
    for (let d = state.harianStart; d <= todayIso(); d = addDays(d, 1))
      if (!state.penggunaan.some(x => x.rutin && x.tgl === d)) { kurang = setRutin(d, harianOn(d)) || kurang; n++; }
    if (n) { save(); renderStok(); if (kurang) showToast('Stok kedelai habis saat produksi rutin dicatat, mohon periksa stok.', true); }
  }
  const catatPakai = (jadwal, jml) => { state.now = Math.round((state.now - jml) * 100) / 100; state.penggunaan.unshift({ tgl: todayIso(), jadwal, jml }); };

  window.mulaiProduksi = async function(id) {
    const o = state.orders.find(x => x.id === id); if (!o) return;
    if (todayIso() < o.mulai) { showToast(`Produksi baru bisa dimulai pada ${o.mulai}.`, true); return; }
    const r = await dlg('Mulai produksi', `Pesanan #${o.id} - ${o.nama}`, [{ l: 'Pemakaian kedelai aktual (kg)', v: butuh(o) }], 'Mulai produksi');
    if (!r) return; const v = r[0];
    if (isNaN(v) || v <= 0) { showToast('Jumlah tidak valid.', true); return; }
    if (v > state.now) { showToast('Stok kedelai tidak cukup!', true); return; }
    o.status = 'Diproduksi'; catatPakai(`#${o.id} - ${o.nama}`, v); save(); renderStok(); renderOrders();
    showToast(`Produksi dimulai! Stok berkurang ${fmt(v)} kg.`);
  };

  window.selesaikanProduksi = function(id) {
    const o = state.orders.find(x => x.id === id); if (!o) return;
    o.status = 'Selesai'; save(); renderOrders(); renderStok(); showToast('Pesanan selesai!');
  };

  window.produksiRutin = async function() {
    const d = todayIso(), e = state.penggunaan.find(x => x.rutin && x.tgl === d);
    const r = await dlg('Koreksi produksi rutin hari ini', 'Produksi rutin otomatis tercatat sesuai rencana. Isi jika pemakaian aktual berbeda.', [{ l: 'Pemakaian aktual (kg)', v: e ? e.jml : harianOn(d) }], 'Simpan');
    if (!r || isNaN(r[0]) || r[0] < 0) { if (r) showToast('Jumlah tidak valid.', true); return; }
    setRutin(d, r[0]); save(); renderStok(); if (document.getElementById('modalHarian').classList.contains('show')) renderHarian(); showToast(`Produksi rutin hari ini: ${fmt(r[0])} kg.`);
  };

  const psnKg = d => state.orders.filter(o => ['Dijadwalkan', 'Diproduksi'].includes(o.status) && o.mulai === d).reduce((a, o) => a + butuh(o), 0);

  window.openJadwalModal = function() {
    const g = {};
    state.orders.filter(o => ['Dijadwalkan', 'Diproduksi'].includes(o.status)).forEach(o => { (g[o.mulai] = g[o.mulai] || []).push(o); });
    const rows = Object.keys(g).sort().map(m => `<tr><td>${m}</td><td>${addDays(m, LEAD)}</td><td>${g[m].map(o => '#' + o.id + ' ' + esc(o.nama)).join(', ')}</td><td>${fmt(harianOn(m))} + ${fmt(psnKg(m))} = ${fmt(harianOn(m) + psnKg(m))} kg</td></tr>`);
    document.getElementById('tbJadwalBody').innerHTML = rows.join('') || '<tr><td colspan="4" style="text-align:center;">Belum ada jadwal.</td></tr>';
    document.getElementById('jadwalInfo').textContent = 'Kolom kedelai = produksi harian + kebutuhan pesanan. Jumlah produksi harian diatur di menu Produksi Harian.';
    document.getElementById('modalJadwal').classList.add('show');
  };

  function renderHarian() {
    document.getElementById('harianInfo').textContent = `Standar: ${fmt(state.harian)} kg/hari. Produksi harian otomatis tercatat di laporan tiap hari (✔), ada pesanan atau tidak. Tanggal yang tidak diubah memakai standar; mengubah satu tanggal tidak memengaruhi tanggal lain maupun pesanan.`;
    let h = '';
    for (let i = -2; i < 12; i++) {
      const d = T(i), ov = state.rencana[d] != null, ps = psnKg(d), rec = state.penggunaan.find(x => x.rutin && x.tgl === d), val = rec ? rec.jml : harianOn(d);
      h += `<tr><td>${d.slice(8)}/${d.slice(5, 7)}</td><td>${fmt(val)}${ov ? ' ✎' : ''}${rec ? ' ✔' : ''}</td><td>${fmt(ps)}</td><td>${fmt(val + ps)}</td><td style="display:flex;flex-direction:column;gap:4px;border:0"><button class="btn-act btn-edit" onclick="pilihHarian('${d}')">Ubah</button>${ov ? `<button class="btn-act btn-cancel" onclick="resetHarian('${d}')">Reset</button>` : ''}</td></tr>`;
    }
    document.getElementById('tbHarianBody').innerHTML = h;
  }
  window.openHarianModal = function() { closeModals(); pilihHarian(todayIso()); renderHarian(); document.getElementById('modalHarian').classList.add('show'); sidebar.classList.remove('show'); };
  window.pilihHarian = d => { document.getElementById('hrTgl').value = d; document.getElementById('hrKg').value = harianOn(d); };
  document.getElementById('hrTgl').onchange = e => { if (e.target.value) document.getElementById('hrKg').value = harianOn(e.target.value); };
  window.simpanHarian = function() {
    const t = document.getElementById('hrTgl').value, v = parseFloat(document.getElementById('hrKg').value);
    if (!t || isNaN(v) || v < 0) { showToast('Isi tanggal dan jumlah kedelai dengan benar.', true); return; }
    if (v === state.harian) delete state.rencana[t]; else state.rencana[t] = v;
    syncRutin(t); save(); renderStok(); renderHarian(); showToast(`Produksi harian ${t}: ${fmt(v)} kg (hanya tanggal ini).`);
  };
  window.resetHarian = d => { delete state.rencana[d]; syncRutin(d); save(); renderStok(); renderHarian(); showToast(`${d} kembali ke standar ${fmt(state.harian)} kg.`); };
  window.ubahStandar = async function() {
    const r = await dlg('Ubah standar produksi harian', 'Berlaku untuk semua tanggal yang tidak diubah khusus.', [{ l: 'Standar (kg/hari)', v: state.harian }]);
    if (!r || isNaN(r[0]) || r[0] < 0) return; state.harian = r[0]; syncRutin(todayIso()); save(); renderStok(); renderHarian(); showToast('Standar diperbarui.');
  };

  window.aturStok = async function() {
    sidebar.classList.remove('show');
    const r = await dlg('Atur stok & produksi harian', 'Stok aktual, batas minimum, dan produksi rutin standar per hari.', [{ l: 'Stok awal / aktual (kg)', v: state.now }, { l: 'Batas minimum stok (kg)', v: state.min }, { l: 'Produksi rutin standar (kg/hari)', v: state.harian }]);
    if (!r) return;
    if (r.some(x => isNaN(x) || x < 0)) { showToast('Nilai tidak valid.', true); return; }
    state.now = r[0]; state.min = r[1]; state.harian = r[2]; state.max = Math.max(state.max, r[0]); save(); renderStok(); showToast('Pengaturan diperbarui.');
  };

  window.resetData = async function() {
    sidebar.classList.remove('show');
    if (await dlg('Reset semua data?', 'Semua pesanan, riwayat penerimaan, penggunaan, dan rencana produksi harian akan dihapus, stok kembali 0. Akun login tetap ada. Tindakan ini tidak bisa dibatalkan.', [], 'Ya, hapus semua') === null) return;
    Object.assign(state, { now: 0, min: 100, max: 1000, orders: [], riwayat: [], penggunaan: [], harian: HARIAN, rencana: {}, harianStart: todayIso() });
    save(); renderStok(); renderOrders(); renderRiwayatPenerimaan(); renderLaporanData();
    showToast('Data direset. Isi stok awal untuk mulai.');
    await aturStok(); postHarian();
  };

  window.logout = async function() {
    if (await dlg('Keluar dari akun?', 'Anda akan kembali ke halaman login.', [], 'Keluar') === null) return;
    await api('/auth/logout'); location.reload(); return;
    ['user', 'pw'].forEach(i => document.getElementById(i).value = ''); document.getElementById('msg').textContent = '';
    show(login); showToast('Anda sudah keluar.');
  };

  function switchLapTab(tabName, btn) {
    document.querySelectorAll('.lap-tab').forEach(t => t.classList.remove('active')); btn.classList.add('active');
    document.querySelectorAll('.lap-view-section').forEach(sec => sec.classList.remove('active'));
    document.getElementById({ produksi: 'secProduksi', pesanan: 'secPesanan', stok: 'secStok' }[tabName]).classList.add('active');
  }

  function lapData() {
    const sel = document.getElementById('lapPeriodeSelect');
    if (!sel.options.length) {
      const d = new Date(); d.setDate(d.getDate() - ((d.getDay() + 6) % 7));
      for (let i = 0; i < 4; i++) { const st = iso(d), en = addDays(st, 6); sel.add(new Option(`${st} s/d ${en}${i ? '' : ' (minggu ini)'}`, st)); d.setDate(d.getDate() - 7); }
    }
    const a = sel.value, z = addDays(a, 6), inP = t => t >= a && t <= z;
    const ord = state.orders.filter(o => inP(o.tanggal)), rc = state.riwayat.filter(r => inP(r.tgl)), pk = state.penggunaan.filter(p => inP(p.tgl));
    const sum = (arr, f) => arr.reduce((x, y) => x + f(y), 0);
    return { a, z, ord, rc, pk, sum, masuk: sum(rc, r => r.jml), keluar: sum(pk, p => p.jml),
      stokAkhir: state.now - sum(state.riwayat.filter(r => r.tgl > z), r => r.jml) + sum(state.penggunaan.filter(p => p.tgl > z), p => p.jml) };
  }

  function renderLaporanData() {
    const L = lapData(), set = (id, v) => document.getElementById(id).textContent = v;
    const kgAll = L.sum(L.ord, butuh), kgDone = L.sum(L.ord.filter(o => o.status === 'Selesai'), butuh);
    const pct = kgAll ? Math.round(kgDone / kgAll * 100) : 0;
    { const ru = L.sum(L.pk.filter(x => x.rutin), x => x.jml); document.getElementById('lapValProduksi').innerHTML = `${fmt(L.keluar)} kg<small style="display:block;font-size:11px;font-weight:600">rutin ${fmt(ru)} + pesanan ${fmt(L.keluar - ru)}</small>`; } set('lapValPesananDiproses', fmt(kgAll) + ' kg'); set('lapValPct', pct + '%');
    document.getElementById('lapFillPct').style.width = pct + '%';
    set('lapValKedelaiMasuk', fmt(L.masuk) + ' kg'); set('lapValKedelaiKeluar', fmt(L.keluar) + ' kg'); set('lapValStokAkhir', fmt(L.stokAkhir) + ' kg');
    const pp = f => fmt(L.sum(L.ord.filter(f), o => o.jml)) + ' Papan';
    set('lapValJumlahPesanan', pp(() => true)); set('lapPsnSelesai', pp(o => o.status === 'Selesai'));
    set('lapPsnJadwal', pp(o => ['Dijadwalkan', 'Diproduksi'].includes(o.status))); set('lapPsnMenunggu', pp(o => o.status === 'Menunggu'));
  }

  function lapRows(kind, L) {
  if (kind === 'pesanan') return { t: 'Riwayat Pesanan', h: ['ID', 'Pelanggan', 'Jumlah (papan)', 'Kebutuhan (kg)', 'Tgl Permintaan', 'Mulai Produksi', 'Alamat', 'Status'],
    r: L.ord.map(o => [o.id, o.nama, o.jml, butuh(o), o.tanggal, o.mulai || mulaiOf(o), o.alamat || '-', o.status]) };
  if (kind === 'penerimaan') return { t: 'Penerimaan Kedelai', h: ['Tanggal', 'Sumber', 'Jumlah (kg)', 'Harga/kg (Rp)', 'Total (Rp)'],
    r: L.rc.map(x => [x.tgl, x.sumber, x.jml, x.harga, x.jml * x.harga]) };
  return { t: 'Penggunaan Kedelai', h: ['Tanggal', 'Untuk', 'Jumlah (kg)'], r: L.pk.map(x => [x.tgl, x.jadwal, x.jml]) };
}

  function lapDetail(kind) {
    const L = lapData(), D = lapRows(kind, L);
    const tab = (k, n) => `<button class="btn-act ${k === kind ? 'btn-flow' : 'btn-edit'}" onclick="lapDetail('${k}')">${n}</button>`;
    odT.textContent = `Laporan ${L.a} s/d ${L.z}`;
    odB.innerHTML = `<div style="display:flex;gap:8px;flex-wrap:wrap;margin:6px 0 12px">${tab('pesanan', 'Riwayat Pesanan')}${tab('penerimaan', 'Penerimaan')}${tab('penggunaan', 'Penggunaan')}</div>
      <h4 style="margin-bottom:8px">${D.t}</h4><div style="overflow-x:auto"><table class="tbl-riwayat"><thead><tr>${D.h.map(x => `<th>${x}</th>`).join('')}</tr></thead>
      <tbody>${D.r.map(r => `<tr>${r.map(c => `<td>${esc(typeof c === 'number' ? fmt(c) : c)}</td>`).join('')}</tr>`).join('') || `<tr><td colspan="${D.h.length}" style="text-align:center">Tidak ada data pada periode ini.</td></tr>`}</tbody></table></div>`;
    odA.innerHTML = '';
    const dl = document.createElement('button'); dl.className = 'm-ok'; dl.textContent = 'Unduh PDF'; dl.onclick = () => unduhLaporan(kind);
    const c = document.createElement('button'); c.className = 'm-no'; c.textContent = 'Tutup'; c.onclick = () => odModal.classList.remove('show');
    odA.append(c, dl); odModal.classList.add('show');
  }

  function buatPDF(judul, sub, head, rows) {
    const W = 842, H = 595, M = 36, RH = 18, FS = 9, U = W - 2 * M;
    const wt = head.map((h, i) => Math.min(28, Math.max(h.length, 5, ...rows.map(r => String(r[i]).length))));
    const tot = wt.reduce((a, b) => a + b, 0), cw = wt.map(w => w / tot * U);
    const cl = t => String(t).replace(/[^\x20-\x7E]/g, '?').replace(/([\\()])/g, '\\$1');
    const cut = (t, w) => { t = String(t); const n = Math.floor((w - 6) / (FS * 0.52)); return t.length > n ? t.slice(0, Math.max(1, n - 1)) + '.' : t; };
    const txt = (f, sz, x, y, t) => `BT /${f} ${sz} Tf ${x.toFixed(1)} ${y.toFixed(1)} Td (${cl(t)}) Tj ET\n`;
    const pages = []; let c = '', y = 0;
    const header = () => { c += `0.9 g ${M} ${y - RH + 4} ${U} ${RH} re f 0 g\n`; let x = M; head.forEach((h, i) => { c += txt('F2', FS, x + 3, y - 9, cut(h, cw[i])); x += cw[i]; }); y -= RH; };
    const page = first => { if (c) pages.push(c); c = ''; y = H - M; if (first) { c += txt('F2', 15, M, y - 12, judul) + txt('F1', 10, M, y - 28, sub); y -= 44; } header(); };
    page(true);
    rows.forEach(r => {
      if (y - RH < M) page(false);
      let x = M; r.forEach((v, i) => { c += txt('F1', FS, x + 3, y - 9, cut(v, cw[i])); x += cw[i]; });
      c += `0.8 G ${M} ${y - RH + 4} m ${M + U} ${y - RH + 4} l S\n`; y -= RH;
    });
    if (!rows.length) c += txt('F1', 10, M + 3, y - 12, 'Tidak ada data pada periode ini.');
    pages.push(c);
    let out = '%PDF-1.4\n'; const off = [];
    const add = (n, b) => { off[n] = out.length; out += `${n} 0 obj\n${b}\nendobj\n`; };
    add(1, '<< /Type /Catalog /Pages 2 0 R >>');
    add(2, `<< /Type /Pages /Kids [${pages.map((_, i) => (5 + 2 * i) + ' 0 R').join(' ')}] /Count ${pages.length} >>`);
    add(3, '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>');
    add(4, '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>');
    pages.forEach((pc, i) => {
      add(5 + 2 * i, `<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ${W} ${H}] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents ${6 + 2 * i} 0 R >>`);
      add(6 + 2 * i, `<< /Length ${pc.length} >>\nstream\n${pc}endstream`);
    });
    const n = 5 + 2 * pages.length, xr = out.length;
    out += `xref\n0 ${n}\n0000000000 65535 f \n` + off.slice(1).map(o => String(o).padStart(10, '0') + ' 00000 n \n').join('') + `trailer\n<< /Size ${n} /Root 1 0 R >>\nstartxref\n${xr}\n%%EOF`;
    return new Blob([out], { type: 'application/pdf' });
  }

  function unduhLaporan(kind) {
    const L = lapData(), D = lapRows(kind, L);
    const blob = buatPDF('Laporan ' + D.t + ' - Si Tempi', `Periode ${L.a} s/d ${L.z} | Dicetak ${todayIso()}`, D.h, D.r.map(r => r.map(c => typeof c === 'number' ? fmt(c) : c)));
    const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
    a.download = `laporan-${kind}-${L.a}_${L.z}.pdf`; document.body.appendChild(a); a.click(); a.remove();
    showToast('Laporan diunduh: ' + a.download);
  }

  function cetakLaporan() {
    const act = document.querySelector('.lap-tab.active').textContent.trim();
    lapDetail({ Produksi: 'penggunaan', Pesanan: 'pesanan', Stok: 'penerimaan' }[act]);
  }

  window.openPenggunaanModal = function() {
    document.getElementById('tbPenggunaanBody').innerHTML = state.penggunaan.length
      ? state.penggunaan.map(p => `<tr><td>${esc(p.tgl)}</td><td>${esc(p.jadwal)}</td><td>${fmt(p.jml)} kg</td></tr>`).join('')
      : '<tr><td colspan="3" style="text-align:center;">Belum ada riwayat penggunaan.</td></tr>';
    document.getElementById('modalPenggunaan').classList.add('show');
  };

  window.openNotifModal = function() {
    const w = peringatan();
    document.getElementById('notifListContent').innerHTML = w.length ? w.map(x => `<div style="color:#9A4B2B;font-weight:700;margin-bottom:8px">⚠️ ${x}</div>`).join('') : '<div>Tidak ada peringatan stok baru.</div>';
    document.getElementById('modalNotif').classList.add('show');
  };

  const odModal = document.getElementById('odModal');
  const odT = document.getElementById('odT');
  const odB = document.getElementById('odB');
  const odA = document.getElementById('odA');

  function openOrderModal(o) {
    const butuhKedelai = o.jml * KEDELAI_PER_SATUAN;
    odT.textContent = 'Detail Pesanan #' + o.id;

    let warnHtml = '';
    if (o.status === 'Menunggu' && butuhKedelai > bebas()) {
      warnHtml = `<div class="dwarn">⚠️ Stok kedelai kurang! Butuh ${fmt(butuhKedelai)} kg, stok bebas ${fmt(bebas())} kg.</div>`;
    }

    odB.innerHTML = `
      <div class="drow"><span>Pelanggan</span><span>${esc(o.nama)}</span></div>
      <div class="drow"><span>Jumlah Pesanan</span><span>${fmt(o.jml)} papan</span></div>
      <div class="drow"><span>Kebutuhan Kedelai</span><span>± ${fmt(butuhKedelai)} kg</span></div>
      <div class="drow"><span>Tanggal Permintaan</span><span>${esc(o.tanggal)}</span></div>
      <div class="drow"><span>Alamat</span><span>${esc(o.alamat || '-')}</span></div>
      <div class="drow"><span>Persetujuan</span><span>${o.status !== 'Menunggu' ? 'Disetujui' : 'Belum Disetujui'}</span></div>
      <div class="drow"><span>Status Pesanan</span><span>${esc(o.status)}</span></div>
      ${warnHtml}
    `;

    odA.innerHTML = '';
    const btnBatal = document.createElement('button');
    btnBatal.className = 'm-no';
    btnBatal.textContent = 'Tutup';
    btnBatal.onclick = () => odModal.classList.remove('show');

    odA.appendChild(btnBatal);
    odModal.classList.add('show');
  }

  odModal.onclick = e => { if (e.target === odModal) odModal.classList.remove('show'); };

  document.querySelectorAll('.nv').forEach(btn => {
    btn.onclick = () => {
      const targetId = btn.dataset.target;
      if (targetId === 'dash') show(dash);
      else if (targetId === 'list') show(list);
      else if (targetId === 'laporanPage') show(laporanPage);
      else if (targetId === 'logout') logout();
      else showToast(btn.textContent.trim() + ' (contoh)');
    };
  });
  if (window.BOOT.user) postHarian(); let lastDay = todayIso(); setInterval(() => { if (todayIso() !== lastDay) { lastDay = todayIso(); postHarian(); } }, 60000);
  if (window.BOOT.user) enter(window.BOOT.user);
</script>
</body>
</html>
@endverbatim
