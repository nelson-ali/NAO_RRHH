for file in *.js; do
    uglifyjs "$file" --stats -c -m  -o "$file" 
    echo minified: "$file" 
done 
