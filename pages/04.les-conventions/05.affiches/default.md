---
title: 'Affiches de conventions'
process:
    markdown: true
    twig: true
twig_first: true
---
{% set affiches = page.children|first.media.images %}


[gallery]
{% for affiche in affiches %}
  {{ affiche.html | raw }}
{% endfor %}
[/gallery]
