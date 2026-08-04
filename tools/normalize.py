# -*- coding: utf-8 -*-
"""
MenúVital — Puerto a Python de la lógica de normalización de ingredientes de
includes/ingredients.php (normalize_ingredient, ingredients_match), para que
el pipeline de construcción de recetas use exactamente el mismo criterio de
coincidencia que usará la app en producción.
"""
import re
import unicodedata

STOPWORDS = {'de', 'en', 'la', 'el', 'con'}

_ACCENTS = str.maketrans({
    'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u', 'ü': 'u', 'ñ': 'n',
})


def normalize_ingredient(s: str) -> str:
    """Réplica exacta de normalize_ingredient() en includes/ingredients.php."""
    s = s.strip().lower()
    s = s.translate(_ACCENTS)
    s = re.sub(r'[^a-z0-9 ]', '', s)
    words = [w for w in s.split(' ') if w and w not in STOPWORDS]
    out = []
    for w in words:
        if len(w) > 4 and w.endswith('s') and not w.endswith('es'):
            out.append(w[:-1])
        elif len(w) > 5 and w.endswith('es'):
            out.append(w[:-2])
        else:
            out.append(w)
    return ' '.join(out)


def ingredients_match(a: str, b: str) -> bool:
    """Réplica exacta de ingredients_match() en includes/ingredients.php."""
    na, nb = normalize_ingredient(a), normalize_ingredient(b)
    if not na or not nb:
        return False
    if na == nb:
        return True
    wa, wb = na.split(' '), nb.split(' ')
    shorter, longer = (wa, wb) if len(wa) <= len(wb) else (wb, wa)
    return all(w in longer for w in shorter)
