<?php require_once 'config.php';
$errors = []; $d = ['name'=>'','email'=>'','subject'=>'','message'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($d as $k => $_) $d[$k] = trim($_POST[$k] ?? '');
    if ($d['name'] === '') $errors['name'] = 'Required';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email';
    if ($d['message'] === '') $errors['message'] = 'Required';
    if (!$errors) {
        $pdo->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)')->execute(array_values($d));
        flash('success', 'Thank you! Your message has been sent.'); redirect('contact.php');
    }
}
$pageTitle = 'Contact Us'; require 'includes/header.php'; ?>
<section class="block"><h2 class="title">Contact Us</h2>
<div class="detail"><div class="panel" style="margin:0"><h3>Get in Touch</h3><br>
<p><strong>Address:</strong> 21 Fashion Street, Ahmedabad, Gujarat 380001</p><p><strong>Phone:</strong> +91 98765 43210</p><p><strong>Email:</strong> support@trendykart.com</p><p><strong>Hours:</strong> Mon–Sat, 10 AM – 7 PM</p></div>
<form method="post" class="panel" style="margin:0"><?= csrf_field() ?>
<label>Name</label><input name="name" value="<?= e($d['name']) ?>"><span class="err"><?= $errors['name'] ?? '' ?></span>
<label>Email</label><input name="email" value="<?= e($d['email']) ?>"><span class="err"><?= $errors['email'] ?? '' ?></span>
<label>Subject</label><input name="subject" value="<?= e($d['subject']) ?>">
<label>Message</label><textarea name="message" rows="4"><?= e($d['message']) ?></textarea><span class="err"><?= $errors['message'] ?? '' ?></span><br><br>
<button class="btn">Send Message</button></form></div></section>
<?php require 'includes/footer.php'; ?>
