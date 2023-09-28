import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

import manifestSRI from 'vite-plugin-manifest-sri';

import fs from 'fs'; 
 
const host = 'my-app.test'; 

export default defineConfig({
    base : "/laravel-vite-react/",
    plugins: [
       
        laravel({
            input: ['resources/js/app.js','resources/js/app.jsx'],
            //refresh: ['resources/views/**'],
            refresh: [{
                paths: ['resources/views/**'],
                config: { delay: 300 }
            }],
        }),
        react(),
        manifestSRI(),
    ],
});