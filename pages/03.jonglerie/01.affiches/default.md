---
title: 'Affiches de conventions'
---

# Exposition d'affiches de convention

{% set affiches = page.children|first.media.images %}

[gallery]
{% for affiche in affiches %}
  {{ affiche.html | raw }}
{% endfor %}
[/gallery]