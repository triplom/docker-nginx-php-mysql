import { Terminal } from 'node_modules/@xterm/xterm/lib/xterm.js';
import { WebLinksAddon } from 'node_modules/@xterm/addon-web-links/lib/WebLinksAddon.js';

// Create a new Terminal instance
const terminal = new Terminal();
terminal.open(document.getElementById('terminal'));

// Load WebLinksAddon on terminal to enable web links
terminal.loadAddon(new WebLinksAddon());

// Initial greeting and prompt
terminal.write('Hello from \x1B[1;3;31mxterm.js\x1B[0m $ ');

let currentPath = '/';
let fileSystem = {
    '/': {
        'home': {},
        'docs': {}
    },
    '/home': {
        'user': {}
    },
    '/docs': {}
};

// Utility function to list files in the current directory
function listFiles(path) {
    let files = Object.keys(fileSystem[path]);
    return files.length === 0 ? 'No files found' : files.join('\n');
}

// Utility function to change directories
function changeDirectory(path) {
    if (fileSystem[path]) {
        currentPath = path;
        return `Changed to ${path}`;
    } else {
        return `No such directory: ${path}`;
    }
}

// Handle input from the user
function handleInput(command) {
    let [cmd, ...args] = command.split(' ');

    switch (cmd) {
        case 'ls':
            terminal.writeln(listFiles(currentPath));
            break;
        case 'cd':
            if (args.length === 0) {
                terminal.writeln('Usage: cd <directory>');
            } else {
                let newPath = (currentPath === '/' ? '' : currentPath) + '/' + args[0];
                terminal.writeln(changeDirectory(newPath));
            }
            break;
        case 'pwd':
            terminal.writeln(currentPath);
            break;
        case 'clear':
            terminal.clear();
            break;
        case 'help':
            terminal.writeln(`Available commands:
ls - list files
cd <directory> - change directory
pwd - print current directory
clear - clear the terminal
help - show this help message`);
            break;
        case 'exit':
            terminal.writeln("Exiting terminal");
            setTimeout(() => {
                terminal.dispose();
            }, 2000);
            return;
        default:
            terminal.writeln(`Unknown command: ${cmd}`);
    }
}

// Prompt and input handling
let commandBuffer = '';
terminal.onData(e => {
    switch (e) {
        case '\r': // Enter key
            terminal.write('\r\n');
            handleInput(commandBuffer);
            commandBuffer = '';
            terminal.write(`${currentPath} $ `);
            break;
        case '\u0003': // Ctrl+C
            terminal.write('^C');
            commandBuffer = '';
            terminal.write('\r\n' + `${currentPath} $ `);
            break;
        case '\u007F': // Backspace (DEL)
            // Do not delete the prompt
            if (terminal._core.buffer.x > `${currentPath} $ `.length) {
                terminal.write('\b \b');
                if (commandBuffer.length > 0) {
                    commandBuffer = commandBuffer.substr(0, commandBuffer.length - 1);
                }
            }
            break;
        default: // Print all other characters for valid commands
            if (e >= String.fromCharCode(0x20) && e <= String.fromCharCode(0x7E)) {
                commandBuffer += e;
                terminal.write(e);
            }
    }
});

// Initial prompt
terminal.write(`${currentPath} $ `);
