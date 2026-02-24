---
description: Push latest changes to GitHub after modifications
---

# Push to GitHub Workflow

Run these steps after making code changes to push to the Oz6ix/HEKA-CMS repository.

// turbo-all

1. Stage all changes:
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern" && git add -A
```

2. Create a commit with a descriptive message:
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern" && git commit -m "COMMIT_MESSAGE_HERE"
```

3. Push to GitHub (uses PAT stored in environment):
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern" && git push origin main 2>&1
```

Note: The remote URL must include authentication. Before the first push in a session, run:
```bash
cd "/Users/sittpaing/Desktop/HEKA Vibe coding Antigravity/heka-modern" && git remote set-url origin https://Oz6ix:$GITHUB_PAT@github.com/Oz6ix/HEKA-CMS.git
```
Where $GITHUB_PAT is the user's Personal Access Token.
