#!/bin/bash
set -e
REPO="https://Noors1234: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@github.com/Noors1234/bedouin-dictionary-site.git"
mkdir -p audio
touch audio/.gitkeep

if [ ! -d .git ]; then
  git init
  git branch -M main
  git remote add origin "$REPO"
fi

git add .
git commit -m "تحديث تلقائي $(date)" || echo "لا توجد تغييرات جديدة"
git push -u origin main --force
