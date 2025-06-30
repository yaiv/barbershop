import path from 'path'
import fs from 'fs'
import { glob } from 'glob'
import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'
import sharp from 'sharp'

const sass = gulpSass(dartSass)

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    imagenes: 'src/img/**/*'
}

export function css(done) {
    console.log('🎨 Compilando CSS...')
    return src(paths.scss, {sourcemaps: true})
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', function(error) {
            // Muestra el error pero no detiene el proceso
            console.log('❌ Error en SCSS:')
            console.log(error.message)
            console.log('🔄 Corrige el error y guarda el archivo para intentar de nuevo')
            this.emit('end') // Continúa el proceso
        }))
        .pipe(dest('./public/build/css', {sourcemaps: '.'}))
        .on('end', () => console.log('✅ CSS compilado correctamente'))
}

export function js(done) {
    console.log('📦 Compilando JavaScript...')
    return src(paths.js)
        .pipe(terser().on('error', function(error) {
            console.log('❌ Error en JavaScript:')
            console.log(error.message)
            console.log('🔄 Corrige el error y guarda el archivo para intentar de nuevo')
            this.emit('end')
        }))
        .pipe(dest('./public/build/js'))
        .on('end', () => console.log('✅ JavaScript compilado correctamente'))
}

export async function imagenes(done) {
    console.log('🖼️  Procesando imágenes...')
    try {
        const srcDir = './src/img';
        const buildDir = './public/build/img';
        const images = await glob('./src/img/**/*')

        images.forEach(file => {
            const relativePath = path.relative(srcDir, path.dirname(file));
            const outputSubDir = path.join(buildDir, relativePath);
            procesarImagenes(file, outputSubDir);
        });
        console.log('✅ Imágenes procesadas correctamente')
    } catch (error) {
        console.log('❌ Error procesando imágenes:', error.message)
    }
    done();
}

function procesarImagenes(file, outputSubDir) {
    if (!fs.existsSync(outputSubDir)) {
        fs.mkdirSync(outputSubDir, { recursive: true })
    }
    const baseName = path.basename(file, path.extname(file))
    const extName = path.extname(file)

    if (extName.toLowerCase() === '.svg') {
        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        fs.copyFileSync(file, outputFile);
    } else {
        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
        const outputFileAvif = path.join(outputSubDir, `${baseName}.avif`);
        const options = { quality: 80 };

        sharp(file).jpeg(options).toFile(outputFile);
        sharp(file).webp(options).toFile(outputFileWebp);
        sharp(file).avif().toFile(outputFileAvif);
    }
}

export function dev() {
    console.log('👀 Gulp está observando cambios en tus archivos...')
    console.log('🔧 Si hay errores, se mostrarán aquí pero el watch continuará')
    console.log('📝 Corrige los errores y guarda para recompilar automáticamente')
    console.log('🛑 Para detener, presiona Ctrl+C')
    console.log('---')
    
    watch(paths.scss, css);
    watch(paths.js, js);
    watch('src/img/**/*.{png,jpg}', imagenes)
}

export default series(js, css, imagenes, dev)