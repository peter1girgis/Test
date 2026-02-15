import { copyFileSync, existsSync } from 'fs';

const source = 'public/build/.vite/manifest.json';
const dest = 'public/build/manifest.json';

try {
  if (existsSync(source)) {
    copyFileSync(source, dest);
    console.log('✓ Manifest copied successfully from .vite/ to root');
  } else {
    console.warn('⚠ Source manifest not found at:', source);
  }
} catch (err) {
  console.error('✗ Failed to copy manifest:', err);
  process.exit(1);
}
