npm run build;
npx cap copy;
npx cap sync;

if [ $1 -eq 1 ]; then
    npx cap run android
else
    npx cap run android -l --external
fi