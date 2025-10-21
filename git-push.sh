#!/bin/bash
# Удобный скрипт для push в GitHub с использованием токена из Replit Secrets
# Использование: ./git-push.sh [branch]

BRANCH=${1:-main}

echo "📤 Pushing to GitHub repository arm-new (branch: $BRANCH)..."
git push https://armx2020:${GITHUB_PERSONAL_ACCESS_TOKEN}@github.com/armx2020/arm-new.git $BRANCH

if [ $? -eq 0 ]; then
    echo "✅ Successfully pushed to GitHub!"
    echo "🔗 https://github.com/armx2020/arm-new"
else
    echo "❌ Push failed. Check your changes and try again."
    exit 1
fi
