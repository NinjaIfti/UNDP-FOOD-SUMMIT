import { CheckCircle, ExternalLink, AlertCircle } from 'lucide-react';

export function VisaTab() {
  return (
    <div className="max-w-4xl mx-auto space-y-8">
      <h2 className="text-3xl font-bold text-gray-900">Visa & Entry Process</h2>

      {/* Summit Visa Support */}
      <div className="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-400 rounded-xl p-6 shadow-md">
        <div className="flex items-start gap-4">
          <CheckCircle className="w-8 h-8 text-amber-600 flex-shrink-0 mt-1" />
          <div className="space-y-4">
            <h3 className="text-2xl font-bold text-gray-900">
              Summit Visa Support
            </h3>
            <div className="space-y-3 text-gray-800">
              <div className="flex items-start gap-3">
                <span className="text-amber-600 font-bold">✓</span>
                <p><strong>Visa fee included:</strong> BTN 5,000 (covered in Summit registration)</p>
              </div>
              <div className="flex items-start gap-3">
                <span className="text-amber-600 font-bold">✓</span>
                <p><strong>Validity:</strong> 10 days to cover the Summit period</p>
              </div>
              <div className="flex items-start gap-3">
                <span className="text-amber-600 font-bold">✓</span>
                <p><strong>Organized by:</strong> Summit organizing team</p>
              </div>
              <div className="flex items-start gap-3">
                <span className="text-amber-600 font-bold">✓</span>
                <p><strong>SDF waived:</strong> Sustainable Development Fee waived during Summit dates</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Important Notes */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div className="flex items-start gap-4">
          <AlertCircle className="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" />
          <div className="space-y-4">
            <h3 className="text-xl font-semibold text-gray-900">
              Important Notes
            </h3>
            <ul className="space-y-3 text-gray-700">
              <li className="flex items-start gap-3">
                <span className="text-[#B91C1C] mt-1">•</span>
                <span>You <strong>cannot enter Bhutan before</strong> your visa start date</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-[#B91C1C] mt-1">•</span>
                <span>Route permits may be required for travel outside Paro/Thimphu</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-[#B91C1C] mt-1">•</span>
                <span>Passport must be valid for at least 6 months from entry date</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="text-[#B91C1C] mt-1">•</span>
                <span>Keep a digital and physical copy of your visa approval letter</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Independent Visa */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Applying Independently
        </h3>
        <p className="text-gray-700 mb-4">
          If you're traveling outside the Summit dates or arranging your own visa:
        </p>
        <div className="space-y-3 mb-6">
          <div className="flex items-start gap-3 text-gray-700">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Standard visa fee: <strong>USD 65</strong></span>
          </div>
          <div className="flex items-start gap-3 text-gray-700">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Sustainable Development Fee (SDF): <strong>USD 100 per night</strong></span>
          </div>
          <div className="flex items-start gap-3 text-gray-700">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Must be arranged through a licensed Bhutanese tour operator</span>
          </div>
        </div>
        <a
          href="https://www.immigration.gov.bt"
          target="_blank"
          rel="noopener noreferrer"
          className="inline-flex items-center gap-2 px-6 py-3 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
        >
          Official Immigration Website
          <ExternalLink className="w-4 h-4" />
        </a>
      </div>
    </div>
  );
}
