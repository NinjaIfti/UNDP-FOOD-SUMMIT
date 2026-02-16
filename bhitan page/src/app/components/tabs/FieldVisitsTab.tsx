import { Info } from 'lucide-react';

export function FieldVisitsTab() {
  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Will There Be Field Visits?</h2>

      <div className="bg-amber-50 border-2 border-amber-300 rounded-xl p-6">
        <div className="flex gap-4">
          <Info className="w-6 h-6 text-amber-600 flex-shrink-0 mt-1" />
          <div className="space-y-3">
            <p className="text-gray-900 text-lg">
              <strong>Optional add-on:</strong> Field visits on 31 August are available at an additional cost and can be selected during registration.
            </p>
            <p className="text-gray-700">
              <em>(Pending confirmation)</em>
            </p>
          </div>
        </div>
      </div>

      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">What to Expect</h3>
        <ul className="space-y-3 text-gray-700">
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Experience Bhutan's unique development approach firsthand</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Visit local communities and sustainable development projects</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Engage with stakeholders on the ground</span>
          </li>
        </ul>
      </div>
    </div>
  );
}
