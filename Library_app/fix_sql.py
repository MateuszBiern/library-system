#!/usr/bin/env python3
# -*- coding: utf-8 -*-

with open('library/library.sql', 'r', encoding='utf-8') as f:
    content = f.read()

# Napraw backtick na końcu UNIQUE KEY
content = content.replace('(book_id,user_ip)`);', '(`book_id`,`user_ip`);')

with open('library/library.sql', 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed SQL")
