import { Terminal } from '@xterm/xterm/lib/xterm.js';
import { AttachAddon } from '@xterm/addon-attach/lib/addon-attach.js';
import { ClipboardAddon } from '@xterm/addon-clipboard/lib/addon-clipboard.js';
import { FitAddon } from '@xterm/addon-fit/lib/addon-fit.js';
import { ImageAddon } from '@xterm/addon-image/lib/addon-image.js';
import { SearchAddon } from '@xterm/addon-search/lib/addon-search.js';
import { SerializeAddon } from '@xterm/addon-serialize/lib/addon-serialize.js';
import { WebLinksAddon } from '@xterm/addon-web-links/lib/addon-web-links.js';

// Create a new Terminal instance
const terminal = new Terminal();

// Load addons
terminal.loadAddon(new WebLinksAddon());
terminal.loadAddon(new ClipboardAddon());
terminal.loadAddon(new FitAddon());
terminal.loadAddon(new ImageAddon());
terminal.loadAddon(new SearchAddon());
terminal.loadAddon(new SerializeAddon());
terminal.loadAddon(new AttachAddon());

// Open the terminal in the specified element
const terminalElement = document.getElementById('terminal');
terminal.open(terminalElement);

// Optional: Fit the terminal to the container's size
const fitAddon = new FitAddon();
terminal.loadAddon(fitAddon);
fitAddon.fit();

// Initial greeting and prompt
terminal.write('Hello from \x1B[1;3;31mxterm.js\x1B[0m $ ');

// Function to handle input (simplified for demonstration)
let commandBuffer = '';
terminal.onData(e => {
    switch (e) {
        case '\r': // Enter key
            terminal.write('\r\n');
            terminal.write(`You typed: ${commandBuffer}`);
            commandBuffer = '';
            terminal.write(`$ `);
            break;
        case '\u0003': // Ctrl+C
            terminal.write('^C');
            commandBuffer = '';
            terminal.write(`$ `);
            break;
        case '\u007F': // Backspace
            if (commandBuffer.length > 0) {
                commandBuffer = commandBuffer.slice(0, -1);
                terminal.write('\b \b');
            }
            break;
        default:
            if (e >= ' ' && e <= '~') {
                commandBuffer += e;
                terminal.write(e);
            }
    }
});

// Initial prompt
terminal.write('$ ');
