/*
 *     ______            __             __
 *    / ____/___  ____  / /__________  / /
 *   / /   / __ \/ __ \/ __/ ___/ __ \/ /
 *  / /___/ /_/ / / / / /_/ /  / /_/ / /
 *  \______________/_/\__/_/   \____/_/
 *     /   |  / / /_
 *    / /| | / / __/
 *   / ___ |/ / /_
 *  /_/ _|||_/\__/ __     __
 *     / __ \___  / /__  / /____
 *    / / / / _ \/ / _ \/ __/ _ \
 *   / /_/ /  __/ /  __/ /_/  __/
 *  /_____/\___/_/\___/\__/\___/
 *
 * Copyright www.controlaltdelete.dev
 */

import { test, expect } from '@playwright/test';
import Checkout from "./Actions/Checkout";

test('Can enter shipping address on Checkout', async ({ page }) => {
    await new Checkout(page).visit();

    const shipping = page.locator('#checkout-step-shipping');
    await shipping.getByLabel('E-mail adres').fill('user@example.com');
    await shipping.getByLabel('Voornaam').fill('John');
    await shipping.getByLabel('Achternaam').fill('Doe');
    await shipping.getByLabel('Adres: Line 1').fill('Paulus Emtinckweg 18');
    await page.getByRole('textbox', { name: 'Postcode*' }).fill('1111bv');
    await shipping.getByLabel('Plaatsnaam').fill('Diemen');
    await shipping.getByLabel('Telefoonnummer').fill('111111111111');

    await page.locator('.table-checkout-shipping-method tbody tr').first().click();

    await page.locator('.primary').getByText('Volgende').click();

    await page.locator('[name="payment[method]"]').first().click();

    await expect(page.getByText('Place Order').first()).toBeVisible();
});
