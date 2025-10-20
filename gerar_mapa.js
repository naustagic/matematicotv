// gerar_mapa.js

const fs = require('fs');
const path = require('path');

// --- CONFIGURAÇÕES ---
// Diretório que será lido (process.cwd() significa o diretório atual onde o script é executado)
const targetDirectory = process.cwd(); 

// Nome do arquivo de saída
const outputFile = 'estrutura.txt';

// Lista de pastas e arquivos a serem ignorados na varredura
const ignoreList = ['node_modules', '.git', '.vscode', outputFile, 'gerar_mapa.js'];
// --------------------


/**
 * Função recursiva para gerar a árvore de diretórios.
 * @param {string} dir - O diretório atual a ser lido.
 * @param {string} prefix - O prefixo para desenhar as linhas da árvore.
 * @returns {string} - A string formatada da árvore para este nível.
 */
function generateTree(dir, prefix = '') {
    let tree = '';
    
    // Lê os arquivos do diretório e filtra os ignorados
    const files = fs.readdirSync(dir).filter(file => !ignoreList.includes(file));

    files.forEach((file, index) => {
        const filePath = path.join(dir, file);
        const stats = fs.statSync(filePath);

        // Define os conectores da árvore (se é o último item da pasta ou não)
        const isLast = index === files.length - 1;
        const connector = isLast ? '└── ' : '├── ';
        
        tree += prefix + connector + file + '\n';

        if (stats.isDirectory()) {
            // Se for um diretório, continua a varredura recursivamente
            const newPrefix = prefix + (isLast ? '    ' : '│   ');
            tree += generateTree(filePath, newPrefix);
        }
    });

    return tree;
}

try {
    console.log(`🔎 Lendo a estrutura do diretório: ${targetDirectory}`);
    
    // Gera a árvore começando pelo nome da pasta raiz
    const rootDirName = path.basename(targetDirectory);
    const finalTree = rootDirName + '\n' + generateTree(targetDirectory);

    // Salva a árvore no arquivo de saída
    fs.writeFileSync(outputFile, finalTree);
    
    console.log(`\n✨ Mapa do site salvo com sucesso no arquivo: ${outputFile} ✨`);

} catch (error) {
    console.error('❌ Ocorreu um erro ao gerar o mapa do site:', error);
}