<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$db = Database::getInstance();
$account = $db->fetch("SELECT * FROM accounts WHERE id = ?", [$id]);
if (!$account) {
    http_response_code(404);
    echo "Conta não encontrada";
    exit;
}

$site_name = getSetting('site_name');
$site_logo = getSetting('site_logo');
$contact_email = getSetting('contact_email');
$contact_phone = getSetting('contact_phone');
$contact_discord = getSetting('contact_discord');
$phone_link = preg_replace('/\D+/', '', (string)$contact_phone);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($account['title']); ?> • <?php echo htmlspecialchars($site_name); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
    body { font-family: 'Oxanium', sans-serif; }
  </style>
</head>
<body class="bg-gray-900 text-gray-100">
  <nav class="w-full bg-gray-900 border-b border-gray-800">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="/" class="flex items-center space-x-2">
        <img src="<?php echo $site_logo; ?>" class="h-8" alt="Logo">
        <span class="text-yellow-400 font-bold"><?php echo htmlspecialchars($site_name); ?></span>
      </a>
      <a href="/" class="text-gray-400 hover:text-yellow-400">Voltar</a>
    </div>
  </nav>

  <main class="max-w-6xl mx-auto px-4 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <div>
        <img src="<?php echo $account['image'] ?: 'https://via.placeholder.com/1200x900/1f2937/6b7280?text=Sem+Imagem'; ?>" alt="<?php echo htmlspecialchars($account['title']); ?>" class="w-full rounded-xl shadow-lg object-cover">
      </div>
      <div>
        <h1 class="text-3xl font-bold text-white mb-2"><?php echo htmlspecialchars($account['title']); ?></h1>
        <div class="flex items-center space-x-3 mb-6">
          <?php if (($account['status'] ?? '') === 'available'): ?>
            <span class="px-2 py-1 text-xs rounded-full bg-green-900 text-green-300">Disponível</span>
          <?php elseif (($account['status'] ?? '') === 'sold'): ?>
            <span class="px-2 py-1 text-xs rounded-full bg-red-900 text-red-300">Vendida</span>
          <?php else: ?>
            <span class="px-2 py-1 text-xs rounded-full bg-yellow-900 text-yellow-300">Premium</span>
          <?php endif; ?>
        </div>

        <div class="space-y-3 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-400">Poder (PS)</span>
            <span class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($account['power_score']); ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Nível</span>
            <span class="text-white"><?php echo htmlspecialchars($account['level']); ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Preço</span>
            <span class="text-green-400 font-bold">R$ <?php echo number_format((float)$account['price'], 2, ',', '.'); ?></span>
          </div>
        </div>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-3">
          <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>?subject=<?php echo rawurlencode('[Interesse] ' . $account['title']); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-4 rounded-lg text-center flex items-center justify-center">
            <i data-feather="mail" class="mr-2"></i> Email
          </a>
          <a href="https://wa.me/<?php echo $phone_link; ?>?text=<?php echo rawurlencode('Tenho interesse na conta: ' . $account['title']); ?>" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-center flex items-center justify-center">
            <i data-feather="message-square" class="mr-2"></i> WhatsApp
          </a>
          <a href="<?php echo strpos((string)$contact_discord, 'http') === 0 ? htmlspecialchars($contact_discord) : 'https://discordapp.com/users/' . htmlspecialchars($contact_discord); ?>" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg text-center flex items-center justify-center">
            <i data-feather="message-circle" class="mr-2"></i> Discord
          </a>
        </div>
      </div>
    </div>
  </main>

  <script>feather.replace();</script>
</body>
</html>
