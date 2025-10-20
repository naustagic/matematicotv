<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Dados dinâmicos
$site_name = getSetting('site_name');
$site_logo = getSetting('site_logo');
$primary_color = getSetting('primary_color');
$secondary_color = getSetting('secondary_color');
$accent_color = getSetting('accent_color');

$sections = getSections();
$hero_section = getSection('hero');
$services_section = getSection('services');
$gallery_section = getSection('gallery');
$faq_section = getSection('faq');
$testimonials_section = getSection('testimonials');
$contact_section = getSection('contact');

$services = getServices($services_section['id']);
$accounts = getAccounts('available', 3); // Apenas 3 contas para a seção
$faqs = getFAQs();
$testimonials = getTestimonials(3); // Apenas 3 depoimentos
$partners = getPartners();
$social_media = getSocialMedia();
$contact_info = getContactInfo();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?> • Intermediação Segura de Contas</title>
    <link rel="icon" type="image/x-icon" href="<?php echo $site_logo; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
        :root {
            --primary: <?php echo $primary_color; ?>;
            --secondary: <?php echo $secondary_color; ?>;
            --accent: <?php echo $accent_color; ?>;
        }
        
        /* Custom shapes for sections */
        .custom-shape {
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
        }
        
        .custom-shape-reverse {
            clip-path: polygon(0 10%, 100% 0, 100% 100%, 0 100%);
        }
        
        /* Improved parallax effect */
        .parallax {
            will-change: transform;
            transition: transform 0.1s ease-out;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
body {
            font-family: 'Oxanium', sans-serif;
            scroll-behavior: smooth;
        }
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .scroll-animate {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }
        .animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        .glow-text {
            text-shadow: 0 0 10px rgba(245, 185, 21, 0.7);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <!-- Vanta.js Background -->
    <div id="vanta-bg"></div>
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-gray-900 bg-opacity-90 backdrop-filter backdrop-blur-lg border-b border-yellow-500 border-opacity-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="<?php echo $site_logo; ?>" alt="Logo">
                        <span class="ml-2 text-yellow-400 font-bold text-xl"><?php echo $site_name; ?></span>
                    </div>
                </div>
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="#home" class="text-yellow-400 hover:text-yellow-300 px-3 py-2 text-sm font-medium">Home</a>
                    <a href="#services" class="text-gray-300 hover:text-yellow-300 px-3 py-2 text-sm font-medium">Serviços</a>
                    <a href="#gallery" class="text-gray-300 hover:text-yellow-300 px-3 py-2 text-sm font-medium">Galeria</a>
                    <a href="#faq" class="text-gray-300 hover:text-yellow-300 px-3 py-2 text-sm font-medium">FAQ</a>
                    <a href="#contact" class="text-gray-300 hover:text-yellow-300 px-3 py-2 text-sm font-medium">Contato</a>
                    <a href="admin/login.php" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                        <i data-feather="disc" class="mr-2"></i> Entrar
                    </a>
</div>
                <div class="-mr-2 flex items-center md:hidden">
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                        <i data-feather="menu"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#home" class="text-yellow-400 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="#services" class="text-gray-300 hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium">Serviços</a>
                <a href="#gallery" class="text-gray-300 hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium">Galeria</a>
                <a href="#faq" class="text-gray-300 hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium">FAQ</a>
                <a href="#contact" class="text-gray-300 hover:text-yellow-300 block px-3 py-2 rounded-md text-base font-medium">Contato</a>
                <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center justify-center mt-2">
                    <i data-feather="disc" class="mr-2"></i> Entrar
                </button>
            </div>
        </div>
    </nav>
        <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-80 z-10"></div>
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="hero-bg" style="background-image: url('<?php echo $hero_section['background_image']; ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black opacity-70 z-10"></div>
<div class="relative z-20 text-center px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 glow-text text-yellow-400">
                <?php echo $hero_section['title']; ?>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-8">
                <?php echo $hero_section['subtitle']; ?>
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button onclick="navigateTo('buy')" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition duration-300 text-lg flex items-center justify-center">
                    <i data-feather="shopping-bag" class="mr-2"></i> Comprar Conta
                </button>
                <button onclick="navigateTo('sell')" class="bg-transparent hover:bg-gray-800 text-yellow-400 font-bold py-3 px-6 border-2 border-yellow-400 rounded-lg transition duration-300 text-lg flex items-center justify-center">
                    <i data-feather="dollar-sign" class="mr-2"></i> Vender Conta
                </button>
</div>
        </div>
        <div class="absolute bottom-10 left-0 right-0 flex justify-center z-20">
            <a href="#services" class="animate-bounce">
                <i data-feather="chevron-down" class="text-yellow-400 w-10 h-10"></i>
            </a>
        </div>
    </section>
    <!-- Services Section -->
    <section id="services" class="py-20 relative overflow-hidden">
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="services-bg" style="background-image: url('<?php echo $services_section['background_image']; ?>');"></div>
        <div class="absolute inset-0 bg-black bg-opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-gradient-radial from-yellow-500/10 via-transparent to-black/80 z-0"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16 scroll-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4"><?php echo $services_section['title']; ?></h2>
                <div class="w-20 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    <?php echo $services_section['subtitle']; ?>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($services as $service): ?>
                <div class="bg-gray-900 rounded-xl p-6 shadow-lg card-hover scroll-animate">
                    <div class="text-yellow-400 mb-4">
                        <i data-feather="<?php echo $service['icon']; ?>" class="w-12 h-12"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3"><?php echo $service['title']; ?></h3>
                    <p class="text-gray-400">
                        <?php echo $service['description']; ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Gallery Section -->
    <section id="gallery" class="py-20 relative overflow-hidden">
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="gallery-bg" style="background-image: url('<?php echo $gallery_section['background_image']; ?>');"></div>
        <div class="absolute inset-0 bg-black bg-opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-black via-transparent to-yellow-500/20 z-0"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4"><?php echo $gallery_section['title']; ?></h2>
                <div class="w-20 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    <?php echo $gallery_section['subtitle']; ?>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($accounts as $account): ?>
                <div class="relative group overflow-hidden rounded-xl shadow-lg scroll-animate">
                    <img src="<?php echo $account['image']; ?>" alt="<?php echo $account['title']; ?>" class="w-full h-64 object-cover transition duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-xl font-bold text-white"><?php echo $account['title']; ?></h3>
                        <p class="text-yellow-400 font-semibold">PS: <?php echo $account['power_score']; ?></p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-gray-300">Nível <?php echo $account['level']; ?></span>
                            <span class="text-yellow-400 font-bold">R$ <?php echo number_format($account['price'], 2, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-12 scroll-animate">
                <button onclick="navigateTo('accounts')" class="bg-transparent hover:bg-yellow-500 text-yellow-400 font-bold py-3 px-6 border-2 border-yellow-400 rounded-lg transition duration-300 text-lg hover:text-gray-900 flex items-center justify-center mx-auto">
                    <i data-feather="grid" class="mr-2"></i> Ver Todas as Contas
                </button>
</div>
        </div>
    </section>
    <!-- Stats Section -->
    <section class="py-16 relative overflow-hidden">
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="stats-bg" style="background-image: url('https://static.photos/gaming/1200x630/10');"></div>
        <div class="absolute inset-0 bg-black bg-opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-yellow-500/10 via-transparent to-black/80 z-0"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 scroll-animate">
                    <div class="text-4xl font-bold text-yellow-400 mb-2">500+</div>
                    <div class="text-gray-300">Contas Vendidas</div>
                </div>
                <div class="p-6 scroll-animate">
                    <div class="text-4xl font-bold text-yellow-400 mb-2">100%</div>
                    <div class="text-gray-300">Transações Seguras</div>
                </div>
                <div class="p-6 scroll-animate">
                    <div class="text-4xl font-bold text-yellow-400 mb-2">24/7</div>
                    <div class="text-gray-300">Suporte Online</div>
                </div>
                <div class="p-6 scroll-animate">
                    <div class="text-4xl font-bold text-yellow-400 mb-2">R$ 250k+</div>
                    <div class="text-gray-300">Em Transações</div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section -->
    <section id="faq" class="py-20 relative overflow-hidden">
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="faq-bg" style="background-image: url('<?php echo $faq_section['background_image']; ?>');"></div>
        <div class="absolute inset-0 bg-black bg-opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-black via-transparent to-yellow-500/20 z-0"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 scroll-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4"><?php echo $faq_section['title']; ?></h2>
                <div class="w-20 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    <?php echo $faq_section['subtitle']; ?>
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto space-y-6">
                <?php foreach ($faqs as $faq): ?>
                <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg scroll-animate">
                    <button class="faq-question w-full text-left p-6 flex justify-between items-center focus:outline-none">
                        <span class="text-lg font-medium text-white"><?php echo $faq['question']; ?></span>
                        <i data-feather="plus" class="text-yellow-400 transform transition-transform duration-300"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-6 pt-0 text-gray-300">
                        <p><?php echo $faq['answer']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Testimonials Section -->
    <section class="py-20 relative overflow-hidden">
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="testimonials-bg" style="background-image: url('<?php echo $testimonials_section['background_image']; ?>');"></div>
        <div class="absolute inset-0 bg-black bg-opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-[conic-gradient(at_top_left,_var(--tw-gradient-stops))] from-yellow-500/10 via-black/80 to-transparent z-0"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16 scroll-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-yellow-400 mb-4"><?php echo $testimonials_section['title']; ?></h2>
                <div class="w-20 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    <?php echo $testimonials_section['subtitle']; ?>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="bg-gray-900 rounded-xl p-6 shadow-lg scroll-animate">
                    <div class="flex items-center mb-4">
                        <img src="<?php echo $testimonial['client_photo']; ?>" alt="<?php echo $testimonial['client_name']; ?>" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-bold text-white"><?php echo $testimonial['client_name']; ?></h4>
                            <div class="flex text-yellow-400">
                                <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                <i data-feather="star" class="w-4 h-4 fill-current"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">
                        "<?php echo $testimonial['testimonial']; ?>"
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-yellow-600 to-yellow-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 scroll-animate">PRONTO PARA COMEÇAR?</h2>
            <p class="text-xl text-gray-800 mb-8 max-w-3xl mx-auto scroll-animate">
                Junte-se a centenas de jogadores que já realizaram transações seguras conosco.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 scroll-animate">
                <button onclick="navigateTo('buy')" class="bg-gray-900 hover:bg-gray-800 text-yellow-400 font-bold py-3 px-6 rounded-lg transition duration-300 text-lg">
                    Comprar Conta
                </button>
                <button onclick="navigateTo('sell')" class="bg-white hover:bg-gray-100 text-gray-900 font-bold py-3 px-6 rounded-lg transition duration-300 text-lg">
                    Vender Conta
                </button>
</div>
        </div>
    </section>
    <!-- Contact Section -->
    <section id="contact" class="py-20 relative overflow-hidden">
        <div class="parallax absolute inset-0 bg-fixed bg-cover bg-center" id="contact-bg" style="background-image: url('<?php echo $contact_section['background_image']; ?>');"></div>
        <div class="absolute inset-0 bg-black bg-opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-yellow-500/10 z-0"></div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="scroll-animate">
                    <h2 class="text-3xl font-bold text-yellow-400 mb-6"><?php echo $contact_section['title']; ?></h2>
                    <p class="text-gray-300 mb-8 text-lg">
                        <?php echo $contact_section['subtitle']; ?>
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="text-yellow-400 mr-4 mt-1">
                                <i data-feather="mail" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">Email</h4>
                                <p class="text-gray-400"><?php echo $contact_info['email']; ?></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="text-yellow-400 mr-4 mt-1">
                                <i data-feather="smartphone" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">WhatsApp</h4>
                                <p class="text-gray-400"><?php echo $contact_info['phone']; ?></p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="text-yellow-400 mr-4 mt-1">
                                <i data-feather="message-circle" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">Discord</h4>
                                <p class="text-gray-400"><?php echo $contact_info['discord']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="scroll-animate">
                    <form class="bg-gray-800 rounded-xl p-8 shadow-lg">
                        <div class="mb-6">
                            <label for="name" class="block text-gray-300 mb-2">Seu Nome</label>
                            <input type="text" id="name" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
                        </div>
                        <div class="mb-6">
                            <label for="email" class="block text-gray-300 mb-2">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
                        </div>
                        <div class="mb-6">
                            <label for="subject" class="block text-gray-300 mb-2">Assunto</label>
                            <select id="subject" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
                                <option value="">Selecione...</option>
                                <option value="buy">Comprar Conta</option>
                                <option value="sell">Vender Conta</option>
                                <option value="trade">Troca de Itens</option>
                                <option value="other">Outro</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label for="message" class="block text-gray-300 mb-2">Mensagem</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition duration-300 text-lg">
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-yellow-400 mb-4"><?php echo $site_name; ?></h3>
                    <p class="text-gray-400">
                        Serviços profissionais de intermediação para o jogo Mir4. Segurança e confiabilidade em todas as transações.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-400 hover:text-yellow-400 transition">Home</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-yellow-400 transition">Serviços</a></li>
                        <li><a href="#gallery" class="text-gray-400 hover:text-yellow-400 transition">Galeria</a></li>
                        <li><a href="#faq" class="text-gray-400 hover:text-yellow-400 transition">FAQ</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-yellow-400 transition">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Serviços</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-yellow-400 transition">Compra de Contas</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-400 transition">Venda de Contas</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-400 transition">Troca de Itens</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-400 transition">Avaliação Profissional</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Redes Sociais</h4>
                    <div class="flex space-x-4">
                        <?php foreach ($social_media as $social): ?>
                        <a href="<?php echo $social['url']; ?>" class="text-gray-400 hover:text-yellow-400 transition">
                            <i data-feather="<?php echo $social['platform']; ?>" class="w-5 h-5"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6">
                        <h5 class="text-sm font-semibold text-white mb-2">NEWSLETTER</h5>
                        <div class="flex">
                            <input type="email" placeholder="Seu email" class="px-4 py-2 bg-gray-800 text-white rounded-l-lg focus:outline-none focus:ring-1 focus:ring-yellow-500 w-full">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-4 py-2 rounded-r-lg transition">
                                <i data-feather="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">
                    © 2023 <?php echo $site_name; ?>. Todos os direitos reservados.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-500 hover:text-yellow-400 text-sm transition">Termos de Serviço</a>
                    <a href="#" class="text-gray-500 hover:text-yellow-400 text-sm transition">Política de Privacidade</a>
                    <a href="#" class="text-gray-500 hover:text-yellow-400 text-sm transition">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75">
        <div class="bg-gray-800 rounded-xl p-8 max-w-md w-full relative">
            <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <i data-feather="x"></i>
            </button>
            <h3 class="text-2xl font-bold text-yellow-400 mb-6 text-center">Entrar</h3>
            <form id="loginForm" class="space-y-6">
                <div>
                    <label for="loginEmail" class="block text-gray-300 mb-2">Email</label>
                    <input type="email" id="loginEmail" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
                </div>
                <div>
                    <label for="loginPassword" class="block text-gray-300 mb-2">Senha</label>
                    <input type="password" id="loginPassword" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
                </div>
                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition duration-300 text-lg">
                    Entrar
                </button>
                <div class="text-center text-gray-400">
                    Não tem conta? <a href="#" onclick="navigateTo('register')" class="text-yellow-400 hover:underline">Registre-se</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Navigation function
        function navigateTo(page) {
            switch(page) {
                case 'buy':
                    window.location.href = '/comprar';
                    break;
                case 'sell':
                    window.location.href = '/vender';
                    break;
                case 'accounts':
                    window.location.href = '/contas';
                    break;
                case 'register':
                    window.location.href = '/registrar';
                    break;
            }
        }

        // Login modal functions
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            feather.replace();
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        // Handle login form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            // Here you would typically send to your backend
            console.log('Login attempt:', email, password);
            
            // Simulate successful login
            alert('Login realizado com sucesso! Redirecionando...');
            closeLoginModal();
            // window.location.href = '/dashboard'; // Uncomment for real redirect
        });
        // Close modal when clicking outside
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });
        // Parallax effect for backgrounds
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            const backgrounds = [
                'hero-bg', 'services-bg', 'gallery-bg', 
                'stats-bg', 'faq-bg', 'testimonials-bg', 'contact-bg'
            ];
            
            backgrounds.forEach(bgId => {
                const element = document.getElementById(bgId);
                if (element) {
                    const xPos = -(x * 20 - 10);
                    const yPos = -(y * 20 - 10);
                    element.style.transform = `translate(${xPos}px, ${yPos}px) scale(1.1)`;
                }
            });
        });

        // Initialize Vanta.js with more dramatic effect
        VANTA.NET({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.50,
            scaleMobile: 1.50,
            color: 0xf5b915,
            backgroundColor: 0x111827,
            points: 15.00,
            maxDistance: 25.00,
            spacing: 15.00,
            showDots: false
        });
// Initialize Feather Icons
        feather.replace();
        
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // FAQ accordion
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const answer = button.nextElementSibling;
                const icon = button.querySelector('i');
                
                // Toggle answer visibility
                answer.classList.toggle('hidden');
                
                // Toggle icon
                if (answer.classList.contains('hidden')) {
                    icon.setAttribute('data-feather', 'plus');
                } else {
                    icon.setAttribute('data-feather', 'minus');
                }
                feather.replace();
            });
        });

        // Scroll animation
        const scrollElements = document.querySelectorAll('.scroll-animate');
        
        const elementInView = (el, dividend = 1) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
            );
        };
        
        const displayScrollElement = (element) => {
            element.classList.add('animate-in');
        };
        
        const hideScrollElement = (element) => {
            element.classList.remove('animate-in');
        };
        
        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 1.25)) {
                    displayScrollElement(el);
                } else {
                    hideScrollElement(el);
                }
            });
        };
        
        window.addEventListener('scroll', () => {
            handleScrollAnimation();
        });
        // Section shape divider effect
        function createWaveDivider() {
            const sections = document.querySelectorAll('section[id]');
            sections.forEach((section, index) => {
                if (index > 0) { // Skip first section
                    const divider = document.createElement('div');
                    divider.className = 'absolute -top-px left-0 right-0 h-20 w-full overflow-hidden z-10';
                    divider.innerHTML = `
                        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="absolute bottom-0 left-0 w-full h-full">
                            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" 
                                  opacity=".25" class="fill-current text-gray-900"></path>
                            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" 
                                  opacity=".5" class="fill-current text-gray-900"></path>
                            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" 
                                  class="fill-current text-gray-900"></path>
                        </svg>
                    `;
                    section.insertBefore(divider, section.firstChild);
                }
            });
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            handleScrollAnimation();
            feather.replace();
            createWaveDivider();
        });
</script>
</body>
</html>