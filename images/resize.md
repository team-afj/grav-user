npx @squoosh/cli --resize '{"height": 1024}' --mozjpeg auto CFJ12\(2020\).png.png

# Compresser toutes les images du dossier actuel et des dossiers enfants si la taille est supèrieur a 1 mo:
`find . -size +1000k -exec npx @squoosh/cli --resize '{"height": 1024}' --mozjpeg auto {} \;`
