# Claude Code Instructions for BixCash Project

## Git Push Policy

**CRITICAL: DO NOT PUSH TO GIT AUTOMATICALLY**

- **NEVER** run `git push` after making changes unless explicitly asked by the user
- Always commit changes with `git commit` but STOP there
- Wait for user confirmation before pushing to remote repository
- The user will explicitly request `git push` when they want changes pushed

## Workflow

1. Make requested changes
2. Test the changes
3. Commit to local git: `git commit -m "..."`
4. **STOP and wait for user approval**
5. Only run `git push` when user explicitly asks for it

## Summary

**User must explicitly request git push. Do not push automatically.**
