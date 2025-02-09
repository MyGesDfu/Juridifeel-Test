# Utiliser une image Node.js pour installer les dépendances et démarrer l'application Angular
FROM node:20.10.0

# Répertoire de travail
WORKDIR /app

# Copier les fichiers package.json et package-lock.json
COPY package*.json ./

# Installer les dépendances Angular
RUN npm install

# Copier le code source de l'application
COPY ./src ./src

# Ajouter les permissions nécessaires sur le répertoire de travail
RUN chown -R node:node /app

# Exposer le port de développement Angular (généralement 4200)
EXPOSE 4200

# Lancer l'application Angular en mode développement sous l'utilisateur 'node'
USER node

# Démarrer l'application Angular
CMD ["npm", "start", "--", "--host", "0.0.0.0"]
