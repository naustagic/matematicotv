<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';
requireAuth();

$db = Database::getInstance();
$faqs = $db->fetchAll("SELECT * FROM faqs ORDER BY display_order");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ • Mir4Mediator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
        body { font-family: 'Oxanium', sans-serif; }
        .sortable-ghost { opacity: 0.4; background-color: rgba(245, 185, 21, 0.1); }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 border-r border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="<?php echo getSetting('site_logo'); ?>" alt="Logo">
                    <span class="ml-2 text-yellow-400 font-bold text-xl"><?php echo getSetting('site_name'); ?></span>
                </div>
                <div class="mt-2 text-sm text-gray-400">Painel Administrativo</div>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="dashboard.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Dashboard</a>
                <a href="sections.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Seções</a>
                <a href="accounts.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Contas</a>
                <a href="faqs.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">FAQ</a>
                <a href="testimonials.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Depoimentos</a>
                <a href="partners.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Parceiros</a>
                <a href="settings.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Configurações</a>
                <a href="logout.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Sair</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Perguntas Frequentes</h1>
                <button onclick="openAddModal()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                    <i data-feather="plus" class="mr-2 w-4 h-4"></i> Nova Pergunta
                </button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total de FAQs</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count($faqs); ?></h3>
                        </div>
                        <div class="text-blue-400 bg-blue-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="help-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Ativas</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($faqs, fn($f) => $f['is_active'])); ?></h3>
                        </div>
                        <div class="text-green-400 bg-green-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Inativas</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($faqs, fn($f) => !$f['is_active'])); ?></h3>
                        </div>
                        <div class="text-red-400 bg-red-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQs List -->
            <div class="space-y-4" id="faqs-list">
                <?php foreach ($faqs as $faq): ?>
                <div class="bg-gray-800 rounded-lg p-6" data-id="<?php echo $faq['id']; ?>">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="text-yellow-400 cursor-move mt-1">
                                <i data-feather="menu" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-white mb-2"><?php echo htmlspecialchars($faq['question']); ?></h3>
                                <p class="text-gray-300"><?php echo htmlspecialchars($faq['answer']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="editFaq(<?php echo $faq['id']; ?>)" 
                                    class="text-gray-400 hover:text-yellow-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="toggleFaq(<?php echo $faq['id']; ?>, <?php echo $faq['is_active'] ? 'false' : 'true'; ?>)" 
                                    class="text-gray-400 hover:text-<?php echo $faq['is_active'] ? 'red' : 'green'; ?>-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="<?php echo $faq['is_active'] ? 'eye-off' : 'eye'; ?>" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteFaq(<?php echo $faq['id']; ?>)" 
                                    class="text-gray-400 hover:text-red-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="trash" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="px-2 py-1 rounded-full <?php echo $faq['is_active'] ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                            <?php echo $faq['is_active'] ? 'Ativa' : 'Inativa'; ?>
                        </span>
                        <span class="text-gray-400">Ordem: <?php echo $faq['display_order']; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($faqs)): ?>
            <div class="text-center py-12">
                <i data-feather="help-circle" class="w-16 h-16 text-gray-600 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-400">Nenhuma pergunta cadastrada</h3>
                <p class="text-gray-500 mt-2">Adicione perguntas frequentes para ajudar seus clientes.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="faqModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75 p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-yellow-400" id="modalTitle">Nova Pergunta</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white transition duration-300">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            
            <form id="faqForm" class="p-6 space-y-6">
                <input type="hidden" id="faq_id">
                
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-300 mb-2">Pergunta</label>
                    <input type="text" id="question" required 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300"
                           placeholder="Digite a pergunta...">
                </div>
                
                <div>
                    <label for="answer" class="block text-sm font-medium text-gray-300 mb-2">Resposta</label>
                    <textarea id="answer" rows="6" required 
                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300"
                              placeholder="Digite a resposta..."></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_active" class="w-4 h-4 text-yellow-500 bg-gray-700 border-gray-600 rounded focus:ring-yellow-500 focus:ring-2">
                    <label for="is_active" class="ml-2 text-sm text-gray-300">Pergunta ativa</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                    <button type="button" onclick="closeModal()" 
                            class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-300">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold rounded-lg transition duration-300 flex items-center">
                        <i data-feather="save" class="mr-2 w-4 h-4"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        feather.replace();
        let currentFaqId = null;

        // Initialize sortable
        new Sortable(document.getElementById('faqs-list'), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            handle: '.cursor-move',
            onEnd: function(evt) {
                const faqs = Array.from(document.getElementById('faqs-list').children).map((item, index) => {
                    return {
                        id: item.dataset.id,
                        display_order: index
                    };
                });
                
                fetch('api/faqs.php?action=reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ faqs: faqs })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optional: Show success message
                    }
                });
            }
        });

        function openAddModal() {
            currentFaqId = null;
            document.getElementById('modalTitle').textContent = 'Nova Pergunta';
            document.getElementById('faqForm').reset();
            document.getElementById('faq_id').value = '';
            document.getElementById('is_active').checked = true;
            document.getElementById('faqModal').classList.remove('hidden');
            feather.replace();
        }

        function closeModal() {
            document.getElementById('faqModal').classList.add('hidden');
        }

        function editFaq(id) {
            fetch(`api/faqs.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentFaqId = id;
                        document.getElementById('modalTitle').textContent = 'Editar Pergunta';
                        document.getElementById('faq_id').value = data.faq.id;
                        document.getElementById('question').value = data.faq.question;
                        document.getElementById('answer').value = data.faq.answer;
                        document.getElementById('is_active').checked = data.faq.is_active;
                        document.getElementById('faqModal').classList.remove('hidden');
                        feather.replace();
                    }
                });
        }

        function toggleFaq(id, active) {
            fetch('api/faqs.php?action=toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, is_active: active })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', `Pergunta ${active ? 'ativada' : 'desativada'} com sucesso.`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            });
        }

        function deleteFaq(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não pode ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f5b915',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/faqs.php?action=delete&id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deletado!', 'Pergunta removida com sucesso.', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire('Erro', data.message, 'error');
                        }
                    });
                }
            });
        }

        // Form submission
        document.getElementById('faqForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                question: document.getElementById('question').value,
                answer: document.getElementById('answer').value,
                is_active: document.getElementById('is_active').checked
            };

            if (currentFaqId) {
                formData.id = currentFaqId;
            }

            const url = currentFaqId ? 'api/faqs.php?action=update' : 'api/faqs.php?action=create';
            const method = currentFaqId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', data.message, 'success');
                    closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            });
        });

        // Close modal on outside click
        document.getElementById('faqModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>